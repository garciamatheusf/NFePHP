<?php

namespace generatenfe;

class ObsFisco {

    public $xTexto;
    public $xCampo;

    function getXTexto() {
        return $this->xTexto;
    }

    function getxCampo() {
        return $this->xCampo;
    }

    function setXTexto($xTexto) {
        $this->xTexto = $xTexto;
    }

    function setxCampo($xCampo) {
        $this->xCampo = $xCampo;
    }
}