import {
  ShaderMount,
  halftoneDotsFragmentShader,
  HalftoneDotsTypes,
  HalftoneDotsGrids,
  getShaderColorFromString,
  ShaderFitOptions,
} from '@paper-design/shaders';

function supportsWebGL2() {
  const canvas = document.createElement('canvas');
  return Boolean(canvas.getContext('webgl2'));
}

function mountHeroHalftone(container) {
  const imageUrl = container.dataset.image;

  if (!imageUrl || !supportsWebGL2()) {
    container.classList.add('site-hero__shader--fallback');
    return;
  }

  const image = new Image();
  image.crossOrigin = 'anonymous';
  image.src = imageUrl;

  image.addEventListener('error', () => {
    container.classList.add('site-hero__shader--fallback');
  });

  image.addEventListener('load', () => {
    container.classList.remove('site-hero__shader--fallback');

    new ShaderMount(
      container,
      halftoneDotsFragmentShader,
      {
        u_image: image,
        u_colorFront: getShaderColorFromString(container.dataset.colorFront || '#2b2b2b'),
        u_colorBack: getShaderColorFromString(container.dataset.colorBack || '#f2f1e8'),
        u_type: HalftoneDotsTypes.gooey,
        u_grid: HalftoneDotsGrids.hex,
        u_size: parseFloat(container.dataset.size || '0.5'),
        u_radius: parseFloat(container.dataset.radius || '1.25'),
        u_contrast: parseFloat(container.dataset.contrast || '0.35'),
        u_originalColors: false,
        u_inverted: false,
        u_grainMixer: 0.2,
        u_grainOverlay: 0.15,
        u_grainSize: 0.5,
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
      2,
      1920 * 1080 * 2,
      ['u_image']
    );
  });
}

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-dh-hero-shader]').forEach(mountHeroHalftone);
});
