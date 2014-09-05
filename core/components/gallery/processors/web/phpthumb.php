<?php

/**
 * @var modX $modx
 * @var array $scriptProperties
 * @var Gallery $gallery
 *
 * @package gallery
 */

/* load phpThumb */


if (!class_exists('phpthumb', false)) {
    if (!$modx->loadClass('phpthumb', MODX_CORE_PATH . 'model/phpthumb/', true, true)) {
        $modx->log(modX::LOG_LEVEL_ERROR, '[phpThumbOf] Could not load modPhpThumb class.');
        return '';
    }
}


$debug = $modx->getOption('debug', $scriptProperties, false);

$src = $modx->getOption('src', $scriptProperties, '');
$src = str_replace('+', '%27', urldecode($src));

/* explode tag options */
$ptOptions = $scriptProperties;

if (empty($ptOptions['f'])) {
    $ext = pathinfo($src, PATHINFO_EXTENSION);
    $ext = strtolower($ext);
    switch ($ext) {
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
        case 'bmp':
            $ptOptions['f'] = $ext;
            break;
        default:
            $ptOptions['f'] = 'jpeg';
            break;
    }
}

/* load phpthumb */
$assetsPath = $modx->getOption('gallery.assets_path', $scriptProperties, $modx->getOption('assets_path') . 'components/gallery/');
$cacheDir = $assetsPath . 'cache/';

/* check to make sure cache dir is writable */
if (!is_writable($cacheDir)) {
    if (!$modx->cacheManager->writeTree($cacheDir)) {
        $modx->log(modX::LOG_LEVEL_ERROR, '[phpThumbOf] Cache dir not writable: ' . $assetsPath . 'cache/');
        return '';
    }
}

/* get absolute url of image */
if (strpos($src, '/') != 0 && strpos($src, 'http') != 0) {
    $src = $modx->getOption('base_url') . $src;
} else {
    $src = urldecode($src);
}
/* auto-prepend base path if not a URL */
if (strpos($src, 'http') === false) {
    $basePath = $modx->getOption('base_path', null, MODX_BASE_PATH);
    if ($basePath != '/') {
        $src = str_replace(basename($basePath), '', $src);
        $src = ltrim($src, '/');
        $src = $basePath . $src;
    }
}

if (!isset($config['modphpthumb'])) { // make sure we get a few relevant system settings
    $config['modphpthumb'] = array();
    $config['modphpthumb']['config_allow_src_above_docroot'] = (boolean)$modx->getOption('phpthumb_allow_src_above_docroot', null, false);
    $config['modphpthumb']['zc'] = $modx->getOption('phpthumb_zoomcrop', null, 0);
    $config['modphpthumb']['far'] = $modx->getOption('phpthumb_far', null, 'C');
    $config['modphpthumb']['config_ttf_directory'] = MODX_CORE_PATH . 'model/phpthumb/fonts/';
    $config['modphpthumb']['config_document_root'] = $modx->getOption('phpthumb_document_root', null, '');
}
$phpThumb = new phpthumb(); // unfortunately we have to create a new object for each image!
foreach ($config['modphpthumb'] as $param => $value) { // add MODX system settings
    $phpThumb->$param = $value;
}
foreach ($ptOptions as $param => $value) { // add options passed to the snippet
    $phpThumb->setParameter($param, $value);
}
// try to avert problems when $_SERVER['DOCUMENT_ROOT'] is different than MODX_BASE_PATH
if (!$phpThumb->config_document_root) {
    $phpThumb->config_document_root = MODX_BASE_PATH; // default if nothing set from system settings
}
$phpThumb->config_cache_directory = $assetsPath . 'cache/'; // doesn't matter, but saves phpThumb some frustration
$phpThumb->setSourceFilename($src);

/* setup cache filename that is unique to this tag */
$inputSanitized = str_replace(array(':', '/'), '_', $src);
$cacheFilename = $inputSanitized;
$cacheFilename .= '.' . md5(serialize($scriptProperties));
$cacheFilename .= '.' . (!empty($ptOptions['f']) ? $ptOptions['f'] : 'png');
$cacheKey = $assetsPath . 'cache/' . $cacheFilename;

/* get cache Url */
$assetsUrl = $modx->getOption('gallery.assets_url', $scriptProperties, $modx->getOption('assets_url') . 'components/gallery/');
$cacheUrl = $assetsUrl . 'cache/' . str_replace($cacheDir, '', $cacheKey);
$cacheUrl = str_replace('//', '/', $cacheUrl);

/* ensure we have an accurate and clean cache directory */
$phpThumb->CleanUpCacheDirectory();

/* debugging code */
if ($debug) {
    $mtime = microtime();
    $mtime = explode(" ", $mtime);
    $mtime = $mtime[1] + $mtime[0];
    $tstart = $mtime;
    set_time_limit(0);

    $oldLogTarget = $modx->getLogTarget();
    $oldLogLevel = $modx->getLogLevel();
    $modx->setLogLevel(modX::LOG_LEVEL_DEBUG);
    $logTarget = $modx->getOption('debugTarget', $scriptProperties, '');
    if (!empty($logTarget)) {
        $modx->setLogTarget();
    }
}

/* ensure file has proper permissions */
if (!empty($cacheKey)) {
    $filePerm = (int)$modx->getOption('new_file_permissions', $scriptProperties, '0664');
    @chmod($cacheKey, octdec($filePerm));
}
if ($debug) {
    $mtime = microtime();
    $mtime = explode(" ", $mtime);
    $mtime = $mtime[1] + $mtime[0];
    $tend = $mtime;
    $totalTime = ($tend - $tstart);
    $totalTime = sprintf("%2.4f s", $totalTime);

    $modx->log(modX::LOG_LEVEL_DEBUG, "\n<br />Execution time: {$totalTime}\n<br />");
    $modx->setLogLevel($oldLogLevel);
    $modx->setLogTarget($oldLogTarget);
}
$output = $assetsUrl;


/* check to see if there's a cached file of this already */
if (file_exists($cacheKey)) {
    $modx->log(modX::LOG_LEVEL_DEBUG, '[phpThumbOf] Using cached file found for thumb: ' . $cacheKey);
    $output = str_replace(' ', '%20', $cacheUrl);
} else {
    /* actually make the thumbnail */
    //return $cacheKey;
    if ($phpThumb->GenerateThumbnail()) { // this line is VERY important, do not remove it!
        if ($phpThumb->RenderToFile($cacheKey)) {
            $output = str_replace(' ', '%20', $cacheUrl);
        } else {
            $modx->log(modX::LOG_LEVEL_ERROR, '[phpThumbOf] Could not cache thumb "' . $src . '" to file at: ' . $cacheKey . ' - Debug: ' . print_r($phpThumb->debugmessages, true));
        }
    } else {
        $modx->log(modX::LOG_LEVEL_ERROR, '[phpThumbOf] Could not generate thumbnail: ' . $src . ' - Debug: ' . print_r($phpThumb->debugmessages, true));
    }
}

if (!headers_sent()) {
    $headers = $modx->request->getHeaders();
    $mtime = filemtime($cacheKey);
    if (isset($headers['If-Modified-Since']) && strtotime($headers['If-Modified-Since']) == $mtime) {
        // cache is good, send 304
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', $mtime).' GMT', true, 304);
        exit();
    }
    header('Last-Modified: '.gmdate('D, d M Y H:i:s', $mtime).' GMT', true, 200);
    $phpThumb->setOutputFormat();
    header('Content-Type: '.phpthumb_functions::ImageTypeToMIMEtype($phpThumb->thumbnailFormat));
    header('Content-Disposition: inline; filename="'.basename($src).'"');
}

return file_get_contents($cacheKey);
