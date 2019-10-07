<?php

namespace generatenfe;

class InfRec {

    public $nRec;
    public $tMed;

    function getNRec() {
        return $this->nRec;
    }

    function getTMed() {
        return $this->tMed;
    }

    function setNRec($nRec) {
        $this->nRec = $nRec;
    }

    function setTMed($tMed) {
        $this->tMed = $tMed;
    }
}