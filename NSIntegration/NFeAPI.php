<?php
class NFeAPI{
	private $token = "";
	private $urlEnvioNFe;
	private $urlStatusProcessamento;
	private $urlDownloadNFe;
	private $urlCancelamentoNFe;
	private $urlConsultaSituacaoNFe;
	private $urlCCeNFe;
	private $urlDownloadEventoNFe;
	private $urlInutilizaNFe;
	private $urlConsultaCadastroContribuinte;
	private $urlConsultaStatusWSSefaz;
	private $urlSendNFeMail;
	private $urlListarNSNRecs;
	private $urlPreviaNFe;
	private $urlDownloadInut;
	

	public function __construct(){
		$this->urlEnvioNFe = "https://nfe.ns.eti.br/nfe/issue";
		$this->urlStatusProcessamento = "https://nfe.ns.eti.br/nfe/issue/status";
		$this->urlDownloadNFe = "https://nfe.ns.eti.br/nfe/get";
		$this->urlCancelamentoNFe = "https://nfe.ns.eti.br/nfe/cancel";
		$this->urlConsultaSituacaoNFe = "https://nfe.ns.eti.br/nfe/stats";
		$this->urlCCeNFe = "https://nfe.ns.eti.br/nfe/cce";
		$this->urlDownloadEventoNFe = "https://nfe.ns.eti.br/nfe/get/event";
		$this->urlInutilizaNFe = "https://nfe.ns.eti.br/nfe/inut";
		$this->urlConsultaCadastroContribuinte = "https://nfe.ns.eti.br/util/conscad";
		$this->urlConsultaStatusWSSefaz = "https://nfe.ns.eti.br/util/wssefazstatus";
		$this->urlSendNFeMail = "https://nfe.ns.eti.br/util/resendemail";
		$this->urlListarNSNRecs = "https://nfe.ns.eti.br/util/list/nsnrecs";
		$this->urlPreviaNFe = "https://nfe.ns.eti.br/util/preview/nfe";
		$this->urlDownloadInut = "https://nfe.ns.eti.br/nfe/get/inut";
	}

	public function emitirNFeSincrono($CNPJEmit, $nfe, $tpDown = 'XP'){

		$result = $this->emitirNFe($nfe);

		$retornoEmissao = $result;
		print_r($retornoEmissao);

		if(!($this->isStatusOK($retornoEmissao['status']))){
			return $retornoEmissao;
		}

		$nsNRec = $retornoEmissao['nsNRec'];
		$counter = 0;
		do {
			if ($counter == 0){
				sleep(.25);
			} else {
				sleep(3);
			}

			$counter++;

			$retornoConsulta = $this->consultarStatusProcessamento($CNPJEmit, $nsNRec);
			
			if($this->isStatusOK($retornoConsulta['status'])){ 
				break;
			}

			if(isset($retornoConsulta['cStat'])) {
				if (!$this->isCStatLoteEmProcessamento($retornoConsulta['cStat'])){
					return $retornoConsulta;
				}

			} else {
				return $retornoConsulta;
			}

		} while ($counter < 3);

		if($this->isCStatNFeAutorizada($retornoConsulta['cStat'])){
			$json = json_decode($nfe);
			$tpAmb = $json->NFe->infNFe->ide->tpAmb;
			$retornoXml = $this->downloadNFe($retornoConsulta['chNFe'], $tpAmb, $tpDown);
			
			if (stripos($tpDown, 'x') !== false){
				$retornoConsulta['xml'] = $retornoXml['xml'];
			}
			if (stripos($tpDown, 'p') !== false){
				$retornoConsulta['pdf'] = $retornoXml['pdf'];
			}
		}

		return $retornoConsulta;
	}

	public function emitirNFe($conteudo){
		$result = $this->enviaJsonParaAPI($conteudo, $this->urlEnvioNFe);
		return $result;
	}

	public function consultarStatusProcessamento($CNPJ, $nsNRec){
		$conteudo['CNPJ'] = $CNPJ;
		$conteudo['nsNRec'] = $nsNRec;
		$result = $this->enviaJsonParaAPI(json_encode($conteudo), $this->urlStatusProcessamento);
		return $result;
	}

	public function downloadNFe($chNFe, $tpAmb = "2", $tpDown){
		$conteudo['chNFe'] = $chNFe;
		$conteudo['tpAmb'] = $tpAmb;
		$conteudo['tpDown'] = $tpDown;
		$result = $this->enviaJsonParaAPI(json_encode($conteudo), $this->urlDownloadNFe);
		return $result;
	}
	
	public function cancelaNFe($chNFe, $tpAmb, $dhEvento, $nProt, $xJust){
		$conteudo['chNFe'] = $chNFe;
		$conteudo['tpAmb'] = $tpAmb;
		$conteudo['dhEvento'] = $dhEvento;
		$conteudo['nProt'] = $nProt;
		$conteudo['xJust'] = $xJust;
		$result = $this->enviaJsonParaAPI(json_encode($conteudo), $this->urlCancelamentoNFe);
		return $result;
	}

	public function cceNFe($chNFe, $tpAmb, $dhEvento, $nSeqEvento, $xCorrecao){
		$conteudo['chNFe'] = $chNFe;
		$conteudo['tpAmb'] = $tpAmb;
		$conteudo['dhEvento'] = $dhEvento;
		$conteudo['nSeqEvento'] = $nSeqEvento;
		$conteudo['xCorrecao'] = $xCorrecao;
		$result = $this->enviaJsonParaAPI(json_encode($conteudo), $this->urlCCeNFe);
		return $result;
	}

	public function inutilizaNFe($cUF, $tpAmb, $ano, $CNPJ, $serie, $nNFIni, $nNFFin, $xJust){
		$conteudo['cUF'] = $cUF;
		$conteudo['tpAmb'] = $tpAmb;
		$conteudo['ano'] = $ano;
		$conteudo['CNPJ'] = $CNPJ;
		$conteudo['serie'] = $serie;
		$conteudo['nNFIni'] = $nNFIni;
		$conteudo['nNFFin'] = $nNFFin;
		$conteudo['xJust'] = $xJust;
		$result = $this->enviaJsonParaAPI(json_encode($conteudo), $this->urlInutilizaNFe);
		return $result;
	}

	public function previaNFe($conteudo){
		$result = $this->enviaJsonParaAPI(json_encode($conteudo), $this->urlPreviaNFe);
		return $result;
	}

	public function downloadEventoNFe($chNFe, $tpDown, $tpEvento, $nSeqEvento){
		$conteudo['chNFe'] = $chNFe;
		$conteudo['tpDown'] = $tpDown;
		$conteudo['tpEvento'] = $tpEvento;
		$conteudo['nSeqEvento'] = $nSeqEvento;
		$result = $this->enviaJsonParaAPI(json_encode($conteudo), $this->urlDownloadEventoNFe);
		return $result;
	}

	public function consultaSituacaoNFe($chNFe, $tpAmb){
		$conteudo['chNFe'] = $chNFe;
		$conteudo['tpAmb'] = $tpAmb;
		$result = $this->enviaJsonParaAPI(json_encode($conteudo), $this->urlConsultaSituacaoNFe);
		return $result;
	}

	public function consultaCadastroContribuinte($CNPJEmitente, $UF, $documentoConsulta, $tpConsulta){
		if($tpConsulta == "IE"){
			$conteudo['IE'] = $documentoConsulta;
		}
		else{
			if($tpConsulta == "CNPJ"){
				$conteudo['CNPJ'] = $documentoConsulta;
			}
			else{
				$conteudo['CPF'] = $documentoConsulta;
			}
		}
		$conteudo['CNPJCont'] = $CNPJEmitente;
		$conteudo['UF'] = $UF;
		$result = $this->enviaJsonParaAPI(json_encode($conteudo), $this->urlConsultaCadastroContribuinte);
		return $result;
	}

	public function listarNSNRecs($chNFe){
		$conteudo['chNFe'] = $chNFe;
		$result = $this->enviaJsonParaAPI(json_encode($conteudo), $this->urlListarNSNRecs);
		return $result;
	}

	public function consultaStatusWSSefaz($CNPJCont, $UF, $tpAmb, $versao = "4.00"){
		$conteudo['CNPJCont'] = $CNPJCont;
		$conteudo['UF'] = $UF;
		$conteudo['tpAmb'] = $tpAmb;
		$conteudo['versao'] = $versao;
		$result = $this->enviaJsonParaAPI(json_encode($conteudo), $this->urlConsultaStatusWSSefaz);
		return $result;
	}

	public function downloadInutilizacao($chave, $tpAmb, $tpDown = "XP"){
		$conteudo['chave'] = $chave;
		$conteudo['tpDown'] = $tpDown;
		$conteudo['tpAmb'] = $tpAmb;
		$result = $this->enviaJsonParaAPI(json_encode($conteudo), $this->urlDownloadInut);
		return $result;
	}

	public function envioEmailNFe($chNFe, $enviaEmailDoc = NULL, $email){
		$conteudo['chNFe'] = $chNFe;
		if(is_null($email)){
			$conteudo['enviaEmailDoc'] = $enviaEmailDoc;
		}else{
			$conteudo['email'] = $email;
		}
		$result = $this->enviaJsonParaAPI(json_encode($conteudo), $this->urlSendNFeMail);
		return $result;
	}

	public function salvarDocumento($conteudo, $caminhoEnomeArquivo, $isBase64 = false){
		if(isset($conteudo) and isset($caminhoEnomeArquivo)){
			if($isBase64 == true){
				$this->salvarDocumentoBase64($conteudo, $caminhoEnomeArquivo);
			}
			else{
				$this->salvarDocumentoTodos($conteudo, $caminhoEnomeArquivo);
			}
		}
	}

	public function salvarDocumentoTodos($conteudo, $caminhoEnomeArquivo){
		$fp = fopen($caminhoEnomeArquivo, 'w+');
		fwrite($fp, $conteudo);
		fclose($fp);
	}

	public function salvarDocumentoBase64($conteudo, $caminhoEnomeArquivo){
		$fp = fopen($caminhoEnomeArquivo, 'w+');
		fwrite($fp, base64_decode($conteudo));
		fclose($fp);
	}

	private function wsResultToArray($result){
		return (array)json_decode(($result));
	}

	private function isStatusOK($status){
		return $status == 200;
	}

	private function isCStatNFeAutorizada($cStat){
		return $cStat == 100;
	}

	private function isCStatLoteEmProcessamento($cStat){
		return $cStat == 105;
	}

	private function enviaJsonParaAPI($conteudoAEnviar, $url){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $conteudoAEnviar);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'X-AUTH-TOKEN: ' . $this->token));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$result = curl_exec($ch);
		curl_close($ch);

		return $this->wsResultToArray($result);
	}
}
?>