<?php namespace RedMarlin\Faq\Components;

use Cms\Classes\ComponentBase;
use RedMarlin\Faq\Components\FaqAll;
use RedMarlin\Faq\Models\Question;
use RedMarlin\Faq\Models\Category;

/**
 * Add our own component, to show all the faq, but with pagination and category filter option.
 * @package RedMarlin\Faq\Components;
 */
class FaqAllCategory extends ComponentBase
{
    public $faqs;
    public $filteredCategory = '';
    public function componentDetails()
    {
        return [
            'name'        => 'FAQ - Display All w Cat Filter',
            'description' => 'Displays list of FAQs from all categories, with filter for categories'
        ];
    }

    public function defineProperties()
    {
        return [
            'pageParam' => [
                'title' => 'Page Parameter',
                'description' => 'Parameter as reference for current page shown!',
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'default' => '{{ :page }}'
            ],
            'categoryFilter' => [
                'title' => 'Filter for category',
                'validationPattern' => '^[0-9]+$',
                'description' => 'ID of faq category',
                'type' => 'string',
                'default' => '{{ :catid }}'
            ],
            'limit' => [
                'title'             => 'Limit',
                'description'       => 'Limit list to X questions',
                'default'           => 25,
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'The Limit property can contain only numeric symbols'
            ],
            'sortOrder' => [
                'title'             => 'Sort Order',
                'description'       => 'Choose sort ordering method. Default newest questions first',
                'default'           => 'desc',
                'type'              => 'dropdown',
                'placeholder'       => 'Select sort order',
                'options'           => ['desc'=>'Newest first', 'asc'=>'Oldest first', 'order'=>'User order']
            ]
        ];
    }

    public function onRun()
    {
     
        $query = Question::whereIsApproved('1');
        
        switch ($this->property('sortOrder')) {
            case "desc":
                $query = $query->orderBy('id', 'desc');
                break;
            case "asc":
                $query = $query->orderBy('id', 'asc');
                break;
            case "order":
                $query = $query->orderBy('sort_order');
                break;
        }

        if($catid = $this->property('categoryFilter')) {
            if(!empty($catid)) {
                $query = $query->where('category_id', $catid);
                $this->filteredCategory = optional(Category::find($catid))->title;
            }
        }
        $this->faqs = $query->paginate($this->property('limit', 25), $this->property('pageParam', 1));

    }


}