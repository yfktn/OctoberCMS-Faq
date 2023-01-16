<?php

namespace RedMarlin\Faq\Components;

use Cms\Classes\ComponentBase;
use RedMarlin\Faq\Models\Question;
use RedMarlin\Faq\Models\Category;

class FaqListCategories extends ComponentBase
{
    public $faqCategories = [];

    public function componentDetails()
    {
        return [
            'name'        => 'FAQ list Categories',
            'description' => 'Displays list of FAQ categories'
        ];
    }

    public function defineProperties()
    {
        return [
            'faqListPage' => [
                'title' => 'FAQ List Page',
                'description' => 'Choose FAQ list Page',
                'type' => 'dropdown',
                'default' => 'faq'
            ],
        ];
    }

    public function getfaqListPageOptions()
    {
        return \Cms\Classes\Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->faqCategories = Category::get();
        foreach($this->faqCategories as $faqCat) {
            $faqCat->url = $this->controller->pageUrl($this->property('faqListPage'), [
                'categoryId' => $faqCat->id,
                'categoryFilter' => $faqCat->id,
                'catid' => $faqCat->id,
            ]);
        }
    }
}
