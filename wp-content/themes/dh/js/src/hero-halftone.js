import {
    ShaderMount,
    dotGridFragmentShader,
    DotGridShapes,
    getShaderColorFromString,
    ShaderFitOptions,
} from '@paper-design/shaders';

function supportsWebGL2() {
    const canvas = document.createElement('canvas');
    return Boolean(canvas.getContext('webgl2'));
}

function readCssColor(element, propertyName, fallback) {
    const value = getComputedStyle(element).getPropertyValue(propertyName).trim();
    return value || fallback;
}

function getHeroShaderColors(container) {
    return {
        u_colorBack: getShaderColorFromString(
            readCssColor(container, '--dh-hero-shader-back', '#f8f8f6')
        ),
        u_colorFill: getShaderColorFromString(
            readCssColor(container, '--dh-hero-shader-fill', 'rgba(0, 0, 0, 0.08)')
        ),
    };
}

function mountHeroDotGrid(container) {
    if (!supportsWebGL2()) {
        container.classList.add('site-hero__shader--fallback');
        return null;
    }

    container.classList.remove('site-hero__shader--fallback');

    const mount = new ShaderMount(
        container,
        dotGridFragmentShader,
        {
            ...getHeroShaderColors(container),
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

    return mount;
}

document.addEventListener('DOMContentLoaded', () => {
    const mounts = [];

    document.querySelectorAll('[data-dh-hero-shader]').forEach((container) => {
        const mount = mountHeroDotGrid(container);
        if (mount) {
            mounts.push({ container, mount });
        }
    });

    document.addEventListener('dh-theme-change', () => {
        mounts.forEach(({ container, mount }) => {
            mount.setUniforms(getHeroShaderColors(container));
        });
    });
});
