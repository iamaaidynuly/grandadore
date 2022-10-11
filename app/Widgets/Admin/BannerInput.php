<?php

namespace App\Widgets\Admin;

use Arrilot\Widgets\AbstractWidget;

class BannerInput extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config;

    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $settings = $this->config['params'];
        if (is_array($settings)) {
            $type = $settings['type'];
            if ($type == 'image' && !empty($settings['resize']) && (!isset($settings['hint']) || !empty($settings['hint']))) {
                $this->config['label'] = $this->config['label'] . ' (' . $settings['resize'][1] . 'x' . $settings['resize'][2] . ')';
            }
        } else {
            $type = $settings;
            $settings = [];
        }
        $data = [
            'key' => $this->config['key'],
            'count' => $this->config['count'],
            'as' => $this->config['as'],
            'label' => $this->config['label'],
            'value' => $this->config['value'],
            'settings' => $settings,
        ];

        return view('admin.widgets.banners.' . $type, $data);
    }
}
