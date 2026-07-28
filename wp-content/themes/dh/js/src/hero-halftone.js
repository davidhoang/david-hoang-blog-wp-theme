import {
    ShaderMount,
    dotGridFragmentShader,
    DotGridShapes,
    getShaderColorFromString,
    ShaderFitOptions,
    halftoneDotsFragmentShader,
    HalftoneDotsTypes,
    HalftoneDotsGrids,
} from '@paper-design/shaders';

function supportsWebGL2() {
    const canvas = document.createElement('canvas');
    return Boolean(canvas.getContext('webgl2'));
}

function readCssColor(element, propertyName, fallback) {
    const value = getComputedStyle(element).getPropertyValue(propertyName).trim();
    return value || fallback;
}

function getDotGridColors(container) {
    return {
        u_colorBack: getShaderColorFromString(
            readCssColor(container, '--dh-hero-shader-back', '#f8f8f6')
        ),
        u_colorFill: getShaderColorFromString(
            readCssColor(container, '--dh-hero-shader-fill', 'rgba(0, 0, 0, 0.08)')
        ),
    };
}

function getHalftoneColors(container) {
    return {
        u_colorBack: getShaderColorFromString(
            readCssColor(container, '--dh-hero-shader-back', '#f8f8f6')
        ),
        u_colorFront: getShaderColorFromString(
            readCssColor(container, '--dh-color-text', '#333333')
        ),
    };
}

function loadHeroImage(url) {
    return new Promise((resolve, reject) => {
        const image = new Image();
        image.decoding = 'async';
        image.onload = () => {
            if (!image.complete || image.naturalWidth === 0) {
                reject(new Error('Hero image failed to decode'));
                return;
            }
            resolve(image);
        };
        image.onerror = () => reject(new Error('Hero image failed to load'));
        image.src = url;
    });
}

function mountDotGrid(container) {
    if (!supportsWebGL2()) {
        container.classList.add('site-hero__shader--fallback');
        return null;
    }

    container.classList.remove('site-hero__shader--fallback');

    const mount = new ShaderMount(
        container,
        dotGridFragmentShader,
        {
            ...getDotGridColors(container),
            u_colorStroke: getShaderColorFromString('rgba(0, 0, 0, 0)'),
            u_dotSize: parseFloat(container.dataset.dotSize || '1.6'),
            u_gapX: parseFloat(container.dataset.gapX || '16'),
            u_gapY: parseFloat(container.dataset.gapY || '24'),
            u_strokeWidth: 0,
            u_sizeRange: parseFloat(container.dataset.sizeRange || '0'),
            u_opacityRange: parseFloat(container.dataset.opacityRange || '0.08'),
            u_shape: DotGridShapes.circle,
            u_fit: ShaderFitOptions.cover,
            u_scale: 1,
            u_rotation: 0,
            u_offsetX: 0,
            u_offsetY: 0,
            u_originX: 0.5,
            u_originY: 0.5,
            u_worldWidth: 0,
            u_worldHeight: 0,
        },
        undefined,
        0,
        0,
        2
    );

    return { mount, mode: 'dotgrid' };
}

function mountHalftone(container, image) {
    if (!supportsWebGL2()) {
        container.classList.add('site-hero__shader--fallback');
        return null;
    }

    container.classList.remove('site-hero__shader--fallback');

    const mount = new ShaderMount(
        container,
        halftoneDotsFragmentShader,
        {
            ...getHalftoneColors(container),
            u_image: image,
            u_type: HalftoneDotsTypes.classic,
            u_grid: HalftoneDotsGrids.square,
            u_size: parseFloat(container.dataset.halftoneSize || '0.28'),
            u_radius: parseFloat(container.dataset.halftoneRadius || '1.15'),
            u_contrast: parseFloat(container.dataset.halftoneContrast || '0.42'),
            u_originalColors: false,
            u_inverted: false,
            u_grainMixer: 0.08,
            u_grainOverlay: 0.06,
            u_grainSize: 0.4,
            u_fit: ShaderFitOptions.cover,
            u_scale: 1,
            u_rotation: 0,
            u_offsetX: 0,
            u_offsetY: 0,
            u_originX: 0.5,
            u_originY: 0.5,
            u_worldWidth: 0,
            u_worldHeight: 0,
        },
        undefined,
        0,
        0,
        2
    );

    return { mount, mode: 'halftone' };
}

async function mountHeroShader(container) {
    const imageUrl = (container.dataset.imageUrl || '').trim();

    if (imageUrl) {
        try {
            const image = await loadHeroImage(imageUrl);
            const mounted = mountHalftone(container, image);
            if (mounted) {
                return mounted;
            }
        } catch (error) {
            // Fall through to the procedural dot grid.
        }
    }

    return mountDotGrid(container);
}

function prefersReducedMotion() {
    return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

function observeHeroShader(container) {
    if (prefersReducedMotion()) {
        container.classList.add('site-hero__shader--fallback');
        return Promise.resolve(null);
    }

    if (!('IntersectionObserver' in window)) {
        return mountHeroShader(container);
    }

    return new Promise((resolve) => {
        const observer = new IntersectionObserver(
            (entries) => {
                const entry = entries[0];

                if (!entry || !entry.isIntersecting) {
                    return;
                }

                observer.disconnect();
                mountHeroShader(container).then(resolve);
            },
            {
                rootMargin: '120px 0px',
                threshold: 0,
            }
        );

        observer.observe(container);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const mounts = [];

    Promise.all(
        Array.from(document.querySelectorAll('[data-dh-hero-shader]')).map(async (container) => {
            const mounted = await observeHeroShader(container);
            if (mounted) {
                mounts.push({ container, ...mounted });
            }
        })
    ).then(() => {
        document.addEventListener('dh-theme-change', () => {
            mounts.forEach(({ container, mount, mode }) => {
                if (mode === 'halftone') {
                    mount.setUniforms(getHalftoneColors(container));
                    return;
                }

                mount.setUniforms(getDotGridColors(container));
            });
        });
    });
});
