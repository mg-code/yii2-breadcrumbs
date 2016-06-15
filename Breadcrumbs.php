<?php

namespace mgcode\breadcrumbs;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is wrapper class of \yii\widgets\Breadcrumbs widget.
 * This widget adds data-vocabulary.org markup.
 *
 * @link https://github.com/mg-code/yii2-breadcrumbs
 * @author Maris Graudins <maris@mg-interactive.lv>
 */
class Breadcrumbs extends \yii\widgets\Breadcrumbs
{
    /**
     * @var string the template used to render each inactive item in the breadcrumbs. The token `{link}`
     * will be replaced with the actual HTML link for each inactive item.
     */
    public $itemTemplate = "<li itemscope itemtype=\"http://data-vocabulary.org/Breadcrumb\">{link}</li>\n";

    /**
     * Renders a single breadcrumb item.
     * @param array $link the link to be rendered. It must contain the "label" element. The "url" element is optional.
     * @param string $template the template to be used to rendered the link. The token "{link}" will be replaced by the link.
     * @return string the rendering result
     * @throws InvalidConfigException if `$link` does not have "label" element.
     */
    protected function renderItem($link, $template)
    {
        $encodeLabel = ArrayHelper::remove($link, 'encode', $this->encodeLabels);
        if (array_key_exists('label', $link)) {
            $label = $encodeLabel ? Html::encode($link['label']) : $link['label'];
        } else {
            throw new InvalidConfigException('The "label" element is required for each link.');
        }
        if (isset($link['template'])) {
            $template = $link['template'];
        }
        if (isset($link['url'])) {
            $options = $link;
            $options['itemprop'] = 'url';
            unset($options['template'], $options['label'], $options['url']);
            $label = Html::tag('span', $label, ['itemprop' => 'title']);
            $link = Html::a($label, $link['url'], $options);
        } else {
            $link = $label;
        }
        return strtr($template, ['{link}' => $link]);
    }
}