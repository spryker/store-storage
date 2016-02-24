<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Persistence;

use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Country\Persistence\SpyRegionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Country\Persistence\CountryPersistenceFactory getFactory()
 */
class CountryQueryContainer extends AbstractQueryContainer implements CountryQueryContainerInterface
{

    /**
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery
     */
    public function queryCountries()
    {
        return $this->getFactory()->createCountryQuery();
    }

    /**
     * @param string $iso2Code
     *
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery
     */
    public function queryCountryByIso2Code($iso2Code)
    {
        $query = $this->queryCountries();
        $query
            ->filterByIso2Code($iso2Code);

        return $query;
    }

    /**
     * @return \Orm\Zed\Country\Persistence\SpyRegionQuery
     */
    public function queryRegions()
    {
        return $this->getFactory()->createRegionQuery();
    }

    /**
     * @param string $isoCode
     *
     * @return \Orm\Zed\Country\Persistence\SpyRegionQuery
     */
    public function queryRegionByIsoCode($isoCode)
    {
        $query = $this->queryRegions();
        $query
            ->filterByIso2Code($isoCode);

        return $query;
    }

}
