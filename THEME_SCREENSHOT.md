# How to Create a WordPress Theme Screenshot

## Requirements

WordPress theme preview images (screenshots) must be:
- **Filename:** `screenshot.png` or `screenshot.jpg`
- **Location:** In your theme's root directory (same folder as `style.css`)
- **Recommended size:** 1200x900 pixels (4:3 aspect ratio)
- **Format:** PNG or JPG

## Method 1: Take a Screenshot of Your Live Site (Recommended)

1. **Visit your live site** (blog.davidhoang.com) in a browser
2. **Take a screenshot** of the homepage or a representative page
   - **macOS:** Press `Shift + Command + 4`, then drag to select the browser window
   - **Or use a browser extension** like "Full Page Screen Capture" for Chrome/Firefox
3. **Resize the image** to 1200x900 pixels:
   - Use Preview (macOS): Open image → Tools → Adjust Size → Set to 1200x900
   - Use online tools: https://www.iloveimg.com/resize-image
   - Use command line: `sips -z 900 1200 screenshot.png`
4. **Save as `screenshot.png`** in your theme directory:
   ```
   wp-content/themes/david-hoang/screenshot.png
   ```

## Method 2: Use a Screenshot Tool

### Option A: Browser DevTools
1. Open your site in Chrome/Firefox
2. Open DevTools (F12 or Cmd+Option+I)
3. Click the device toolbar icon (or Cmd+Shift+M)
4. Set custom dimensions: 1200x900
5. Take a screenshot using browser's screenshot feature

### Option B: Online Screenshot Services
- **Screenshot.guru**: https://screenshot.guru/ (enter your URL, set size to 1200x900)
- **BrowserStack**: Take screenshot of your live site

## Method 3: Create a Simple Placeholder (Quick Start)

If you just want something to show up immediately, you can create a simple placeholder image.

### Using Command Line (macOS):
```bash
# Create a simple colored image with text
# Requires ImageMagick (install via: brew install imagemagick)
convert -size 1200x900 xc:"#ffffff" \
  -pointsize 72 -fill "#1a1a1a" \
  -gravity center \
  -annotate +0+0 "David Hoang\nTheme Preview" \
  wp-content/themes/david-hoang/screenshot.png
```

### Using Python (if you have PIL/Pillow):
```python
from PIL import Image, ImageDraw, ImageFont

# Create image
img = Image.new('RGB', (1200, 900), color='white')
draw = ImageDraw.Draw(img)

# Add text (you may need to adjust font path)
text = "David Hoang\nTheme Preview"
draw.text((600, 450), text, fill='black', anchor='mm')

img.save('wp-content/themes/david-hoang/screenshot.png')
```

## After Creating the Screenshot

1. **Place the file** in: `wp-content/themes/david-hoang/screenshot.png`
2. **Refresh WordPress admin**: Go to Appearance → Themes
3. **The preview should appear** automatically

## Troubleshooting

- **Screenshot not showing?** 
  - Make sure the file is named exactly `screenshot.png` (lowercase)
  - Check file permissions: `chmod 644 screenshot.png`
  - Clear browser cache and refresh

- **Wrong size?**
  - WordPress will resize it, but 1200x900 is the standard
  - Use any image editor to resize

- **Want to update it?**
  - Just replace the `screenshot.png` file
  - WordPress will detect the change automatically
