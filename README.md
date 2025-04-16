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

. Project Structure

project-folder/
│
├── upload_extract.php         ← Main file
├── uploads/                   ← Stores uploaded `.dmg` files
├── fontsnew/                  ← Stores extracted fonts
├── fontsnew/fonts_bundle.zip  ← Compressed ZIP archive of fonts
├── README.md                  ← This file

    Make sure both uploads/ and fontsnew/ folders are writable:

chmod -R 777 uploads fontsnew

3. Running the Project

    Place the project on your local server (e.g., XAMPP/WAMP) or use the built-in PHP server:

php -S localhost:8000

    Open in your browser:

http://localhost:8000/upload_extract.php

🌍 Language Switching

Supports both Arabic and English.

    Arabic (RTL): ?lang=ar

    English (LTR): ?lang=en

Example:

http://localhost:8000/upload_extract.php?lang=en

A language toggle button is also available in the UI.
📥 How It Works

    Upload a .dmg file containing Mac fonts.

    Watch the progress bar as the file uploads and is processed.

    After extraction, click the download button to get a .zip file of all fonts.

🛠 Troubleshooting
Issue	Solution
Upload fails or stalls	Check file size limits and folder permissions
Fonts not found	Ensure .pkg and Payload files exist in the .dmg
ZipArchive errors	Install the PHP zip extension
✨ Customization Ideas

    Preview fonts in browser after extraction

    Allow multiple .dmg uploads at once

    Convert fonts to .woff / .woff2 for web usage

    Save uploaded files with user-defined names

Let me know if you'd like help implementing any of these!
🧑‍💻 Contribute

Pull requests are welcome! Feel free to improve the interface, add features, or fix bugs.
📜 License

Open source — free to use, modify, and share.

Made with 💙 by [MOHAMMED GILANI] — Enjoy!


---

### ✅ Next Step?

Would you like:

- A version that includes screenshots or GIFs for GitHub?
- A downloadable `.zip` with this file and full project structure?
- To deploy it on a live server or share it via GitHub?

Let me know and I’ll help you wrap it all up! 🚀

Get smarter responses, upload files and images, and more.


