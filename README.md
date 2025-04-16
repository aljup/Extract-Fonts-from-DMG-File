# 🅵🅾🅽🆃 Extractor from DMG (PHP + Tailwind + AJAX)

A simple web-based tool to upload `.dmg` files (usually containing Mac fonts), extract `.pkg` and `Payload` files, retrieve fonts, and download them as a ZIP archive.

## 💡 Features

- ✅ Upload `.dmg` files via a clean Tailwind CSS interface
- ✅ Extract `.pkg` and `Payload` files using `7z`
- ✅ Retrieve font files (`.ttf`, `.otf`, `.dfont`)
- ✅ Auto-zip extracted fonts into a single downloadable file
- ✅ Visual upload & extraction progress bar with percentage
- ✅ Multilingual support: 🇸🇦 Arabic (RTL) & 🇺🇸 English (LTR)
- ✅ One-click download of bundled fonts

---

## 🚀 How to Use

### 1. Requirements

- PHP 7.4 or higher
- Apache, Nginx, or PHP built-in server
- PHP Extensions:
  - `zip`
  - `fileinfo`
- System Tool:
  - `7z` (p7zip)

> 📦 To install `7z` on Fedora/RHEL/CentOS:

```bash
sudo dnf install p7zip p7zip-plugins
