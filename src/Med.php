<?php

namespace generatenfe;

class Med {

    public $vPMC;
    public $cProdANVISA;


    function getvPMC() {
        return $this->vPMC;
    }

    function getCProdANVISA() {
        return $this->cProdANVISA;
    }

    function setvPMC($vPMC) {
        $this->vPMC = $vPMC;
    }

    function setCProdANVISA($cProdANVISA) {
        $this->cProdANVISA = $cProdANVISA;
    }
}