<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Form;

use Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery;
use Spryker\Zed\Gui\Communication\Form\AbstractForm;
use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Symfony\Component\Form\FormBuilderInterface;

class CarrierForm extends AbstractForm
{

    const FIELD_NAME_GLOSSARY_FIELD = 'glossaryKeyName';
    const FIELD_NAME_FIELD = 'name';
    const FIELD_IS_ACTIVE_FIELD = 'isActive';
    const CARRIER_ID = 'carrier_id';

    /**
     * @var \Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery
     */
    protected $carrierQuery;

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery $carrierQuery
     */
    public function __construct(SpyShipmentCarrierQuery $carrierQuery)
    {
        $this->carrierQuery = $carrierQuery;
    }

    /**
     * @return null
     */
    protected function getDataClass()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'carrier';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_NAME_FIELD, 'text', [
                'label' => 'Name',
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
            ->add(self::FIELD_NAME_GLOSSARY_FIELD, new AutosuggestType(), [
                'label' => 'Name glossary key',
                'url' => '/glossary/ajax/keys',
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ])
            ->add(self::FIELD_IS_ACTIVE_FIELD, 'checkbox', [
                'label' => 'Enabled?',
            ]);
    }

    /**
     * @return array
     */
    public function populateFormFields()
    {
        $result = [];
        $carrierId = $this->getRequest()->get(self::CARRIER_ID);

        if ($carrierId !== null) {
            $carrier = $this->carrierQuery->findOneByIdShipmentCarrier($carrierId);
            $result = [
                self::FIELD_IS_ACTIVE_FIELD => $carrier->getIsActive(),
            ];
        }

        return $result;
    }

}
