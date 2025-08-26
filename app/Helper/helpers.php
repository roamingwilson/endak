<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

if (!function_exists('clean_html')) {
    function clean_html($text = null)
    {
        if ($text) {
            $text = strip_tags($text, '<h1><h2><h3><h4><h5><h6><p><br><ul><li><hr><a><abbr><address><b><blockquote><center><cite><code><del><i><ins><strong><sub><sup><time><u><img><iframe><link><nav><ol><table><caption><th><tr><td><thead><tbody><tfoot><col><colgroup><div><span>');

            $text = str_replace('javascript:', '', $text);
        }
        return $text;
    }
}

if (!function_exists('live_env')) {
    function is_live_env()
    {
        if (env('APP_ENV') == 'live') {
            return true;
        }
        return false;
    }
}

if (!function_exists('str_slug')) {

    function str_slug($title, $separator = '-', $language = 'en')
    {
        return Str::slug($title, $separator, $language);
    }
}
if (!function_exists('unique_slug')) {

    function unique_slug($title = '', $model = 'Course', $skip_id = 0)
    {
        $slug = str_slug($title);

        if (empty($slug)) {
            $string = mb_strtolower($title, "UTF-8");

            $string = preg_replace("/[\/\.]/", " ", $string);
            $string = preg_replace("/[\s-]+/", " ", $string);
            $slug = preg_replace("/[\s_]/", '-', $string);
        }

        //get unique slug...
        $nSlug = $slug;
        $i = 0;

        $model = str_replace(' ', '', "\App\ " . $model);

        if ($skip_id === 0) {
            while (($model::whereSlug($nSlug)->count()) > 0) {
                $i++;
                $nSlug = $slug . '-' . $i;
            }
        } else {
            while (($model::whereSlug($nSlug)->where('id', '!=', $skip_id)->count()) > 0) {
                $i++;
                $nSlug = $slug . '-' . $i;
            }
        }
        if ($i > 0) {
            $newSlug = substr($nSlug, 0, strlen($slug)) . '-' . $i;
        } else {
            $newSlug = $slug;
        }
        return $newSlug;
    }

}

if (!function_exists('no_data')) {
    function no_data($title = '', $desc = '', $class = null)
    {
        $title = $title ? $title : __('general.nothing_here');
        $desc = $desc ? $desc : __('general.nothing_here_desc');
        $class = $class ? $class : 'my-4 pb-4';
        $no_data_img = asset('images/no-data.svg');

        $output = " <div class='no-data-screen-wrap text-center {$class} '>
            <img src='{$no_data_img}' style='max-height: 250px; width: auto' />
            <h3 class='no-data-title'>{$title}</h3>
            <h5 class='no-data-subtitle'>{$desc}</h5>
        </div>";
        return $output;
    }
}
if (!function_exists('icon_classes')) {
    function icon_classes()
    {
        $pattern = '/\.(la-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"\\\\(.+)";\s+}/';
        $subject = file_get_contents(ROOT_PATH . '/assets/css/line-awesome.css');
        preg_match_all($pattern, $subject, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $icons[$match[1]] = $match[2];
        }
        ksort($icons);
        return $icons;
    }
}
if (!function_exists('selected')) {
    function selected($selected, $current = true, $echo = true)
    {
        return __checked_selected_helper($selected, $current, $echo, 'selected');
    }
}

if (!function_exists('__checked_selected_helper')) {
    function __checked_selected_helper($helper, $current, $echo, $type)
    {
        if ((string) $helper === (string) $current)
            $result = " $type='$type'";
        else
            $result = '';

        if ($echo)
            echo $result;

        return $result;
    }
}

if (!function_exists('sendWhatsAppMessage')) {
    function sendWhatsAppMessage($to, $body, $fromNumber = null, $token = null, $instance_id = null)
    {
        // dd($instance_id);
        $token = trim($token ?? '0wu52jmdvqamtsiq');
        $instance = trim($instance_id ?? '132007');
        $params = [
            'token' => $token,
            'to' => $to,
            'body' => $body
        ];
        if ($fromNumber) {
            $params['from'] = $fromNumber;
        }
        $url = "https://api.ultramsg.com/instance{$instance}/messages/chat";
        Log::info('WhatsApp Params: ' . json_encode(['url' => $url, 'params' => $params]));
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_HTTPHEADER => array("content-type: application/x-www-form-urlencoded"),
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 30,
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        Log::info('WhatsApp API response: ' . $response);
        if ($err) {
            Log::error('WhatsApp CURL error: ' . $err);
            return false;
        }
        return $response;
    }
}

if (!function_exists('normalizePhone')) {
    function normalizePhone($phone, $countryCode = '+966')
    {
        // إزالة أي أحرف غير رقمية
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // إزالة رمز البلد إذا كان موجوداً في البداية
        $countryCodeClean = preg_replace('/[^0-9]/', '', $countryCode);
        if (strpos($phone, $countryCodeClean) === 0) {
            $phone = substr($phone, strlen($countryCodeClean));
        }

        // إزالة الصفر من البداية إذا كان موجوداً
        if (strpos($phone, '0') === 0) {
            $phone = substr($phone, 1);
        }

        return $countryCode . $phone;
    }
}



