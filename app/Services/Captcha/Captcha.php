<?php

namespace App\Services\Captcha;
class Captcha
{
    private $default_fonts = array(
        'roboto.bold.ttf',
        'roboto.light.ttf',
        'roboto.medium.ttf',
        'roboto.regular.ttf'
    );

    private function getFontsDir()
    {
        return app_path('Services/Captcha') . '/fonts/';
    }

    private $params;
    private $letters = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '2', '3', '4', '5', '6', '7', '9');
    private $transparent_colors = array('10', '30', '50', '70', '90', '110', '130', '150', '170', '190', '210');
    private $image;
    private $fonts = array();
    private $prefix = 'zcaptcha_';

    private function set_params($params)
    {
        $this->params = (object)array(
            'width' => 150,
            'height' => 60,
            'font_size' => 16,
            'length' => 6,
            'fake_letters_count' => 30,
            'fonts' => $this->default_fonts,
            'background_color_rgb' => [255, 255, 255],
            'type' => 'jpeg',
            'html' => false
        );
        if (count($params) !== 0) foreach ($params as $parameter_key => $parameter_value) {
            if (isset($this->params->{$parameter_key})) {
                $this->params->{$parameter_key} = $parameter_value;
            }
        }
    }

    private function create_image()
    {
        $this->image = imagecreatetruecolor($this->params->width, $this->params->height);
        $bg = $this->params->background_color_rgb;
        $background = imagecolorallocate($this->image, $bg[0], $bg[1], $bg[2]);
        imagefill($this->image, 0, 0, $background);
    }

    private function verify_fonts()
    {
        foreach ($this->params->fonts as $font) {
            $font_name = $this->getFontsDir() . $font;
            if (file_exists($font_name)) {
                $this->fonts[] = $font_name;
            }
        }
        if (count($this->fonts) == 0) return false;
        else return true;
    }

    private function add_fake_letters()
    {
        $count = (integer)$this->params->fake_letters_count;
        if ($count > 0) for ($i = 0; $i < $count; $i++) {
            $alpha = 100;
            $size = rand($this->params->font_size - 2, $this->params->font_size + 2);
            $x = rand($this->params->width * 0.1, $this->params->width - $this->params->width * 0.1);
            $y = rand($this->params->height * 0.2, $this->params->height);
            $angle = rand(0, 90) - 45;
            $this->add_letter($this->transparent_colors, true, $alpha, $size, $x, $y, $angle);
        }
    }

    private function add_text()
    {
        $text = '';
        for ($i = 0; $i < $this->params->length; $i++) {
            $alpha = rand(20, 40);
            $size = rand($this->params->font_size * 2.1 - 2, $this->params->font_size * 2.1 + 2);
            $x = ($i + 0.5) * $this->params->font_size * 1.3 + rand(4, 7);
            $y = (($this->params->height * 2) / 3) + rand(0, 5);
            $angle = rand(0, 30) - 15;
            $text .= $this->add_letter($this->transparent_colors, true, $alpha, $size, $x, $y, $angle);
        }

        return $text;
    }

    private function add_letter($colors, $from_array, $alpha, $size, $x, $y, $angle)
    {
        $rgb = array();
        if ($from_array === false) {
            for ($i = 0; $i < 3; $i++) {
                $rgb[] = rand($colors[0], $colors[1]);
            }
        } else {
            $count = count($colors) - 1;
            for ($i = 0; $i < 3; $i++) {
                $rgb[] = $colors[rand(0, $count)];
            }
        }
        $color = imagecolorallocatealpha($this->image, $rgb[0], $rgb[1], $rgb[2], $alpha);
        $font = $this->fonts[rand(0, count($this->fonts) - 1)];
        $letter = $this->letters[rand(0, sizeof($this->letters) - 1)];
        imagettftext($this->image, $size, $angle, $x, $y, $color, $font, $letter);

        return $letter;
    }

    private function to_base64()
    {
        ob_start();
        $function = 'image' . $this->params->type;
        $function($this->image);
        $image_data = ob_get_clean();
        $base64 = base64_encode($image_data);
        $result = 'data:image/' . $this->params->type . ';base64,' . $base64;

        return $result;
    }

    public function create($name, $params = [])
    {
        $oldName = $name;
        $name = $this->prefix . $name;
        if (is_array($params)) $this->set_params($params);
        else $this->set_params(array());
        if ($this->verify_fonts() === false) return false;
        $length = (int)$this->params->length;
        if ($length <= 0) return false;
        $this->create_image();
        $this->add_fake_letters();
        $text = $this->add_text();
        session([$name => $text]);
        if ($this->params->html === true) {
            $result = self::makeHtml($this->to_base64(), $oldName);
        } else $result = $this->to_base64();

        return $result;
    }

    public function verify($name, $value)
    {
        $name = $this->prefix . $name;
        $text = strtolower($value);
        $string = session($name);
        if (empty($string)) $result = 'expired';
        else if (empty($text) || $text !== $string) $result = 'denied';
        else $result = 'confirmed';
        session()->forget($name);

        return $result;
    }
}
