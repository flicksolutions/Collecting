<?php
namespace Collecting\Form;

use Collecting\Entity\CollectingForm as CollectingFormEntity;
use Omeka\Form\Element\ItemSetSelect;
use Zend\Form\Form;

class CollectingForm extends Form
{
    public function init()
    {
        $this->add([
            'name' => 'o-module-collecting:label',
            'type' => 'Text',
            'options' => [
                'label' => 'Label', // @translate
            ],
            'attributes' => [
                'required' => true,
            ],
        ]);
        $this->add([
            'name' => 'o-module-collecting:anon_type',
            'type' => 'select',
            'options' => [
                'label' => 'Anonymity Type', // @translate
                'info' => 'Control how to treat "User Public" input on public pages.',
                'value_options' => CollectingFormEntity::getAnonTypes(),
            ],
        ]);
        $this->add([
            'name' => 'item_set_id',
            'type' => ItemSetSelect::class,
            'options' => [
                'label' => 'Item Set', // @translate
                'info' => 'Assign all items created by this form to this item set.', // @translate
                'empty_option' => 'Select Item Set...', // @translate
            ],
            'attributes' => [
                'required' => false,
            ],
        ]);

        $filter = $this->getInputFilter();
        $filter->add([
            'name' => 'o-module-collecting:label',
            'required' => true,
        ]);
        $filter->add([
            'name' => 'item_set_id',
            'required' => false,
        ]);
    }
}
