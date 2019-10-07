<?php

namespace generatenfe;

class ICMS51 {

    public $orig;
    public $CST;
    public $modBC;
    public $pRedBC;
    public $vBC;
    public $pICMS;
    public $vICMSOp;
    public $pDif;
    public $vICMSDif;
    public $vICMS;
    public $vBCFCP;
    public $pFCP;
    public $vFCP;

    function getOrig() {
        return $this->orig;
    }

    function getCST() {
        return $this->CST;
    }

    function getModBC() {
        return $this->modBC;
    }

    function getPRedBC() {
        return $this->pRedBC;
    }

    function getvBC() {
        return $this->vBC;
    }

    function getpICMS() {
        return $this->pICMS;
    }

    function getvICMSOp() {
        return $this->vICMSOp;
    }

    function getPDif() {
        return $this->pDif;
    }

    function getvICMSDif() {
        return $this->vICMSDif;
    }

    function getvICMS() {
        return $this->vICMS;
    }

    function getVBCFCP() {
        return $this->vBCFCP;
    }

    function getPFCP() {
        return $this->pFCP;
    }

    function getVFCP() {
        return $this->vFCP;
    }

    function setOrig($orig) {
        $this->orig = $orig;
    }

    function setCST($CST) {
        $this->CST = $CST;
    }

    function setModBC($modBC) {
        $this->modBC = $modBC;
    }

    function setPRedBC($pRedBC) {
        $this->pRedBC = $pRedBC;
    }

    function setvBC($vBC) {
        $this->vBC = $vBC;
    }

    function setpICMS($pICMS) {
        $this->pICMS = $pICMS;
    }

    function setvICMSOp($vICMSOp) {
        $this->vICMSOp = $vICMSOp;
    }

    function setPDif($pDif) {
        $this->pDif = $pDif;
    }

    function setvICMSDif($vICMSDif) {
        $this->vICMSDif = $vICMSDif;
    }

    function setvICMS($vICMS) {
        $this->vICMS = $vICMS;
    }

    function setVBCFCP($vBCFCP) {
        $this->vBCFCP = $vBCFCP;
    }

    function setPFCP($pFCP) {
        $this->pFCP = $pFCP;
    }

    function setVFCP($vFCP) {
        $this->vFCP = $vFCP;
    }

}