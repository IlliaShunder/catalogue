<?php
/*
 * Scandiweb_Test
 *
 * @category Scandiweb
 * @package Test
 * @author Illia Shunder <illia.shunder@scandiweb.com>
 * @copyright Copyright (c) 2022. Scandiweb, Inc (http://scandiweb.com)
 * @license http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(ComponentRegistrar::MODULE, 'Scandiweb_Test', __DIR__);
