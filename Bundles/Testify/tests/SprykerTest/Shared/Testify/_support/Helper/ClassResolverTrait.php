<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Configuration;

trait ClassResolverTrait
{
    use LocatorHelperTrait;

    /**
     * @var array<array-key, string>
     */
    protected $coreNamespaces = [
        'Spryker',
        'SprykerShop',
        'SprykerSdk',
        'SprykerMiddleware',
        'SprykerEco',
    ];

    /**
     * @param string $classNamePattern
     * @param string $moduleName
     *
     * @return object|null
     */
    protected function resolveClass(string $classNamePattern, string $moduleName): ?object
    {
        $resolvedClassName = $this->resolveClassName($classNamePattern, $moduleName);

        if ($resolvedClassName === null) {
            return null;
        }

        return new $resolvedClassName();
    }

    /**
     * @param string $classNamePattern
     * @param string $moduleName
     * @param string|null $applicationName
     *
     * @return string|null
     */
    protected function resolveClassName(string $classNamePattern, string $moduleName, ?string $applicationName = null): ?string
    {
        $classNameCandidates = $this->getClassNameCandidates($classNamePattern, $moduleName, $applicationName);

        foreach ($classNameCandidates as $classNameCandidate) {
            if (class_exists($classNameCandidate)) {
                return $classNameCandidate;
            }
        }

        return null;
    }

    /**
     * @param string $classNamePattern
     * @param string $moduleName
     *
     * @return array<string>
     */
    protected function getClassNameCandidates(string $classNamePattern, string $moduleName): array
    {
        $classNameFromConfiguration = $this->getClassNameFromConfiguration($classNamePattern, $moduleName);

        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);

        // When `application` is configured in the codeception.yml use this value instead of guessing it.
        $application = $config['application'] ?? $namespaceParts[1];

        $classNameCandidates = [];
        $classNameCandidates[] = $classNameFromConfiguration;

        foreach ($this->coreNamespaces as $coreNamespace) {
            $classNameCandidates[] = sprintf($classNamePattern, $coreNamespace, $application, $moduleName);
        }

        return $classNameCandidates;
    }

    /**
     * @param string $classNamePattern
     * @param string $moduleName
     *
     * @return string
     */
    protected function getClassNameFromConfiguration(string $classNamePattern, string $moduleName): string
    {
        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);

        // When `organization` and or `application` is configured in the codeception.yml use these value instead of guessing them.
        $organization = $config['organization'] ?? $namespaceParts[0];
        $application = $config['application'] ?? $namespaceParts[1];

        return sprintf($classNamePattern, $this->trimTestNamespacePostfix($organization), $application, $moduleName);
    }

    /**
     * @param string $namespacePart
     *
     * @return string
     */
    protected function trimTestNamespacePostfix(string $namespacePart): string
    {
        return preg_replace('/Test$/', '', $namespacePart);
    }
}
