(function () {
  if (typeof dhFontSwitcher === 'undefined') {
    return;
  }

  var config = dhFontSwitcher;
  var styleNode = document.getElementById('dh-font-switcher-style');
  var committedFont = config.default;
  var previewing = false;
  var currentVariantFamily = null;
  var previewTimer = null;
  var lastPreviewId = null;
  var pendingPreviewId = null;
  var faceCache = {};

  if (!styleNode) {
    styleNode = document.createElement('style');
    styleNode.id = 'dh-font-switcher-style';
    document.head.appendChild(styleNode);
  }

  function groupFonts(fonts) {
    var groups = {};

    fonts.forEach(function (font) {
      if (!groups[font.family]) {
        groups[font.family] = [];
      }

      groups[font.family].push(font);
    });

    return groups;
  }

  function quoteFamily(name) {
    return '"' + name.replace(/"/g, '\\"') + '"';
  }

  function buildFontFace(font) {
    if (!font.files || !font.files.length) {
      return '';
    }

    return font.files
      .map(function (file) {
        var format = 'opentype';

        if (/\.ttf$/i.test(file.url)) {
          format = 'truetype';
        } else if (/\.woff2$/i.test(file.url)) {
          format = 'woff2';
        } else if (/\.woff$/i.test(file.url)) {
          format = 'woff';
        }

        var weight = file.weight || 400;
        var descriptor = '@font-face { font-family: ' + quoteFamily(font.css_family) + '; src: url("' + file.url + '") format("' + format + '"); font-weight: ' + weight;

        if (file.max) {
          descriptor += ' ' + file.max;
        }

        descriptor += '; font-style: ' + (file.style || 'normal') + '; font-display: swap; }';

        return descriptor;
      })
      .join('\n');
  }

  function fontStack(font) {
    return font.id === 'geist' ? config.default.css_family : quoteFamily(font.css_family) + ', system-ui, sans-serif';
  }

  function updateMeta(font, mode) {
    var meta = document.getElementById('dh-font-switcher-meta');

    if (!meta) {
      return;
    }

    var suffix = font.variable ? ' · variable' : '';
    meta.textContent = (mode === 'preview' ? 'Preview: ' : '') + font.label + suffix;
  }

  function syncFontFaces() {
    styleNode.textContent = Object.keys(faceCache)
      .map(function (key) {
        return faceCache[key];
      })
      .filter(Boolean)
      .join('\n');
  }

  function ensureFontFaces(font) {
    if (!faceCache[font.id]) {
      faceCache[font.id] = buildFontFace(font);
      syncFontFaces();
    }
  }

  function applyFontStack(font) {
    var stack = fontStack(font);
    document.documentElement.style.setProperty('--dh-font-family', stack);
    document.body.style.fontFamily = stack;
  }

  function preloadFont(font) {
    if (font.id === 'geist' || !font.files || !font.files.length) {
      return Promise.resolve();
    }

    ensureFontFaces(font);

    var family = quoteFamily(font.css_family);
    var loaders = font.files.map(function (file) {
      var weight = file.max ? '100 900' : String(file.weight || 400);
      return document.fonts.load(weight + ' 16px ' + family).catch(function () {
        return null;
      });
    });

    return Promise.all(loaders);
  }

  function renderFont(font, mode) {
    ensureFontFaces(font);
    applyFontStack(font);
    updateMeta(font, mode);
  }

  function queuePreview(font) {
    if (!font || font.id === lastPreviewId) {
      return;
    }

    pendingPreviewId = font.id;
    previewing = true;

    if (previewTimer) {
      window.clearTimeout(previewTimer);
    }

    previewTimer = window.setTimeout(function () {
      var target = findFontById(pendingPreviewId) || font;

      preloadFont(target).then(function () {
        if (pendingPreviewId !== target.id) {
          return;
        }

        lastPreviewId = target.id;
        renderFont(target, 'preview');
      });
    }, 140);
  }

  function commitFont(font) {
    if (previewTimer) {
      window.clearTimeout(previewTimer);
      previewTimer = null;
    }

    committedFont = font;
    previewing = false;
    pendingPreviewId = null;
    lastPreviewId = font.id;

    preloadFont(font).then(function () {
      renderFont(font, 'commit');

      try {
        localStorage.setItem(config.storageKey, JSON.stringify({ id: font.id }));
      } catch (error) {
        // Ignore storage failures in private browsing.
      }
    });
  }

  function restoreCommitted() {
    if (previewTimer) {
      window.clearTimeout(previewTimer);
      previewTimer = null;
    }

    previewing = false;
    pendingPreviewId = null;
    lastPreviewId = committedFont.id;
    renderFont(committedFont, 'commit');
  }

  function geistFont() {
    return {
      id: 'geist',
      label: 'Geist',
      css_family: 'Geist',
      files: [],
    };
  }

  function findFontById(id) {
    if (id === config.default.id) {
      return config.default;
    }

    if (id === 'geist') {
      return geistFont();
    }

    return config.fonts.find(function (font) {
      return font.id === id;
    });
  }

  function defaultFontForFamily(family, groups) {
    if (family === 'geist') {
      return config.default;
    }

    return (groups[family] || [])[0] || config.default;
  }

  function setActiveOption(list, id) {
    list.querySelectorAll('.dh-font-switcher__option').forEach(function (button) {
      var isActive = button.dataset.fontId === id;
      button.classList.toggle('is-active', isActive);
      button.setAttribute('aria-selected', isActive ? 'true' : 'false');
    });
  }

  function buildList(list, items, onActivate, onCommit) {
    list.replaceChildren();

    items.forEach(function (item) {
      var button = document.createElement('button');
      button.type = 'button';
      button.className = 'dh-font-switcher__option';
      button.role = 'option';
      button.dataset.fontId = item.id;
      button.textContent = item.label;
      button.setAttribute('aria-selected', 'false');
      button.tabIndex = -1;

      button.addEventListener('pointerenter', function () {
        onActivate(item);
      });

      button.addEventListener('click', function () {
        onCommit(item);
        setActiveOption(list, item.id);
      });

      list.appendChild(button);
    });
  }

  function variantItemsForFamily(family, groups) {
    if (family === 'geist') {
      return [
        {
          id: 'geist',
          label: 'Default stack',
          font: config.default,
        },
      ];
    }

    return (groups[family] || []).map(function (font) {
      return {
        id: font.id,
        label: font.variant,
        font: font,
      };
    });
  }

  function populateVariants(family, variantList, groups, activeId, shouldPreview) {
    if (currentVariantFamily === family && variantList.childElementCount) {
      if (activeId) {
        setActiveOption(variantList, activeId);
      }

      if (shouldPreview) {
        var currentItems = variantItemsForFamily(family, groups);
        var previewTarget = currentItems.find(function (item) {
          return item.id === (activeId || currentItems[0].id);
        });

        if (previewTarget) {
          queuePreview(previewTarget.font);
        }
      }

      return;
    }

    currentVariantFamily = family;

    var items = variantItemsForFamily(family, groups);

    buildList(
      variantList,
      items,
      function (item) {
        queuePreview(item.font);
      },
      function (item) {
        commitFont(item.font);
        setActiveOption(variantList, item.id);
      }
    );

    var resolvedActiveId = activeId || items[0].id;
    setActiveOption(variantList, resolvedActiveId);

    if (shouldPreview) {
      var activeItem = items.find(function (item) {
        return item.id === resolvedActiveId;
      });

      if (activeItem) {
        queuePreview(activeItem.font);
      }
    }
  }

  function activateFamily(family, familyList, variantList, groups, options) {
    var settings = options || {};
    var previewFont = defaultFontForFamily(family, groups);
    var activeVariantId = settings.variantId || previewFont.id;

    setActiveOption(familyList, family === 'geist' ? 'geist' : family);
    populateVariants(family, variantList, groups, activeVariantId, settings.preview !== false);
  }

  function populateFamilies(familyList, variantList, groups) {
    var items = [
      {
        id: 'geist',
        label: 'Geist',
        font: config.default,
      },
    ];

    Object.keys(groups)
      .sort(function (a, b) {
        return a.localeCompare(b, undefined, { sensitivity: 'base' });
      })
      .forEach(function (family) {
        items.push({
          id: family,
          label: family,
          font: defaultFontForFamily(family, groups),
        });
      });

    buildList(
      familyList,
      items,
      function (item) {
        activateFamily(item.id, familyList, variantList, groups, {
          variantId: item.id === 'geist' ? 'geist' : defaultFontForFamily(item.id, groups).id,
          preview: true,
        });
      },
      function (item) {
        if (item.id === 'geist') {
          commitFont(config.default);
          activateFamily('geist', familyList, variantList, groups, {
            variantId: 'geist',
            preview: false,
          });
          return;
        }

        var font = defaultFontForFamily(item.id, groups);
        activateFamily(item.id, familyList, variantList, groups, {
          variantId: font.id,
          preview: false,
        });
        commitFont(font);
      }
    );
  }

  function restoreSelection(familyList, variantList, groups) {
    var savedId = null;

    try {
      var saved = JSON.parse(localStorage.getItem(config.storageKey) || 'null');
      savedId = saved && saved.id ? saved.id : null;
    } catch (error) {
      savedId = null;
    }

    var font = savedId ? findFontById(savedId) : config.default;

    if (!font) {
      font = config.default;
    }

    if (font.id === 'geist') {
      activateFamily('geist', familyList, variantList, groups, {
        variantId: 'geist',
        preview: false,
      });
      commitFont(font);
      return;
    }

    if (font.id === config.default.id) {
      activateFamily(config.default.family, familyList, variantList, groups, {
        variantId: config.default.id,
        preview: false,
      });
      commitFont(config.default);
      return;
    }

    activateFamily(font.family, familyList, variantList, groups, {
      variantId: font.id,
      preview: false,
    });
    commitFont(font);
  }

  function init() {
    var root = document.getElementById('dh-font-switcher');
    var panel = document.getElementById('dh-font-switcher-panel');
    var toggle = root ? root.querySelector('.dh-font-switcher__toggle') : null;
    var familyList = document.getElementById('dh-font-switcher-family');
    var variantList = document.getElementById('dh-font-switcher-variant');

    if (!root || !panel || !toggle || !familyList || !variantList || !config.fonts.length) {
      return;
    }

    var groups = groupFonts(config.fonts);

    if (config.default.files && config.default.files.length) {
      faceCache[config.default.id] = buildFontFace(config.default);
      syncFontFaces();
    }

    populateFamilies(familyList, variantList, groups);
    restoreSelection(familyList, variantList, groups);

    root.hidden = false;

    toggle.addEventListener('click', function () {
      var isOpen = !panel.hidden;
      panel.hidden = isOpen;
      toggle.setAttribute('aria-expanded', String(!isOpen));

      if (isOpen) {
        restoreCommitted();
      }
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
