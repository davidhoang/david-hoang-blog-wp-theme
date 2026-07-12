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

function mountHeroDotGrid(container) {
  if (!supportsWebGL2()) {
    container.classList.add('site-hero__shader--fallback');
    return;
  }

  container.classList.remove('site-hero__shader--fallback');

  new ShaderMount(
    container,
    dotGridFragmentShader,
    {
      u_colorBack: getShaderColorFromString(container.dataset.colorBack || '#f8f8f6'),
      u_colorFill: getShaderColorFromString(container.dataset.colorFill || 'rgba(0, 0, 0, 0.08)'),
      u_colorStroke: getShaderColorFromString(container.dataset.colorStroke || 'rgba(0, 0, 0, 0)'),
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
}

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-dh-hero-shader]').forEach(mountHeroDotGrid);
});
