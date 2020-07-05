<?php

/**
 * @author Ricardo Paes
 * @since 05/07/2020 às 06:43
 */

namespace Tests;

trait IfUseTrait {

    public function assertUseTrait($class, $traitClass) {
        $this->assertTrue( array_key_exists($traitClass, class_uses($class)) );
    }

}
