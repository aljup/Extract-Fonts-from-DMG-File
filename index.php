<?php
// Language handling
$lang = $_GET['lang'] ?? 'ar';
$isArabic = $lang === 'ar';

function t($ar, $en) {
    global $isArabic;
    return $isArabic ? $ar : $en;
}

$uploadDir = __DIR__ . '/uploads/';
$fontsDir = __DIR__ . '/fontsnew/';
$zipFile = 'fontsnew/fonts_bundle.zip';
$status = '';
$zipCreated = false;

function findFileRecursive($dir, $pattern) {
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    $files = [];
    foreach ($rii as $file) {
        if ($file->isDir()) continue;
        if (fnmatch($pattern, $file->getFilename())) {
            $files[] = $file->getPathname();
        }
    }
    return $files;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['dmgFile'])) {
    $filename = basename($_FILES['dmgFile']['name']);
    $targetFile = $uploadDir . $filename;

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!file_exists($fontsDir)) {
        mkdir($fontsDir, 0777, true);
    }

    if (move_uploaded_file($_FILES['dmgFile']['tmp_name'], $targetFile)) {
        $status .= "✅ " . t("تم رفع الملف بنجاح", "File uploaded successfully") . ": $filename<br>";

        $dmgDir = $uploadDir . pathinfo($filename, PATHINFO_FILENAME);
        mkdir($dmgDir);
        exec("7z x '$targetFile' -o'$dmgDir'");

        $pkgFiles = findFileRecursive($dmgDir, '*.pkg');
        if (count($pkgFiles) === 0) {
            $status .= "⚠️ " . t("لم يتم العثور على ملف .pkg", "No .pkg file found") . "<br>";
        } else {
            $pkgFile = $pkgFiles[0];
            $pkgDir = $dmgDir . '/pkg';
            mkdir($pkgDir);
            exec("7z x '$pkgFile' -o'$pkgDir'");

            $payload = '';
            if (file_exists("$pkgDir/Payload")) {
                $payload = "$pkgDir/Payload";
            } elseif (file_exists("$pkgDir/Payload~")) {
                $payload = "$pkgDir/Payload~";
            } else {
                $status .= "⚠️ " . t("لم يتم العثور على ملف Payload", "Payload file not found") . "<br>";
            }

            if ($payload) {
                $payloadDir = $pkgDir . '/payload_extracted';
                mkdir($payloadDir);
                exec("7z x '$payload' -o'$payloadDir'");

                $fontFiles = array_merge(
                    findFileRecursive($payloadDir, '*.ttf'),
                    findFileRecursive($payloadDir, '*.otf'),
                    findFileRecursive($payloadDir, '*.dfont')
                );

                if (empty($fontFiles)) {
                    $status .= "⚠️ " . t("لم يتم العثور على خطوط", "No fonts found in Payload") . "<br>";
                } else {
                    foreach ($fontFiles as $font) {
                        $dest = $fontsDir . basename($font);
                        copy($font, $dest);
                        $status .= "📁 " . t("تم نقل الخط", "Font moved") . ": " . basename($font) . "<br>";
                    }

                    // إنشاء ملف ZIP
                    $zip = new ZipArchive();
                    if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
                        foreach (glob($fontsDir . '*.{ttf,otf,dfont}', GLOB_BRACE) as $font) {
                            $zip->addFile($font, basename($font));
                        }
                        $zip->close();
                        $zipCreated = true;
                        $status .= "📦 " . t("تم إنشاء ملف مضغوط للخطوط", "Fonts zipped successfully") . "<br>";
                    }

                    $status .= "🎉 " . t("اكتملت العملية بنجاح", "Process completed successfully") . "<br>";
                }
            }
        }
    } else {
        $status .= "❌ " . t("فشل في رفع الملف", "File upload failed");
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $isArabic ? 'ar' : 'en' ?>" dir="<?= $isArabic ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <title><?= t("استخراج خطوط من DMG", "Extract Fonts from DMG") ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-100 text-gray-800 font-sans" x-data x-cloak>

    <div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-center w-full">
                📦 <?= t("استخراج الخطوط من ملف DMG", "Extract Fonts from DMG File") ?>
            </h2>
            <a href="?lang=<?= $isArabic ? 'en' : 'ar' ?>" class="text-blue-600 text-sm underline whitespace-nowrap">
                <?= $isArabic ? 'English' : 'العربية' ?>
            </a>
        </div>

        <form id="uploadForm" action="?lang=<?= $lang ?>" method="post" enctype="multipart/form-data" class="space-y-4">
            <label class="block">
                <span class="block mb-1 font-medium"><?= t("اختر ملف DMG", "Choose DMG file") ?></span>
                <input type="file" name="dmgFile" required
                       class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:border-0
                              file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700
                              hover:file:bg-blue-100 border rounded">
            </label>
            <button type="submit"
                    class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition w-full">
                <?= t("رفع و استخراج", "Upload & Extract") ?>
            </button>
        </form>

        <?php if (!empty($status)): ?>
        <div id="result" class="mt-6 bg-green-50 border border-green-200 text-green-800 p-4 rounded text-sm leading-relaxed">
            <?= $status ?>
        </div>
        <?php endif; ?>

        <?php if ($zipCreated): ?>
        <div class="mt-4 text-center">
            <a href="<?= $zipFile ?>" download
               class="inline-block bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition">
                ⬇️ <?= t("تحميل الخطوط مضغوطة", "Download Fonts ZIP") ?>
            </a>
        </div>
        <?php endif; ?>
    </div>

    <script>
        const form = document.getElementById('uploadForm');
        form.addEventListener('submit', () => {
            const btn = form.querySelector('button');
            btn.textContent = "⏳ <?= t("جاري المعالجة...", "Processing...") ?>";
            btn.disabled = true;
            btn.classList.add('opacity-50');
        });
    </script>

</body>
</html>
