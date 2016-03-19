<?php	
	$root=realpath(".");
	$phpinc = realpath("../phpinc");
	$jsinc = "../jsinc";
	require_once "$phpinc/ckinc/session.php";
	cSession::set_folder();
	session_start();
	
	require_once "$phpinc/ckinc/header.php";
	require_once "$phpinc/ckinc/debug.php";
	require_once "$phpinc/ckinc/common.php";
	require_once "$phpinc/ckinc/objstore.php";
	require_once "$phpinc/nasa/spirit.php";
	
	cDebug::check_GET_or_POST();
	cObjStore::$OBJDATA_REALM = "rover";
	$oRover = new cSpiritRover();
	$oInstruments = new cSpiritInstruments();

	$sSol = cHeader::get("s");
	if ($sSol == null){
		$aSols = $oRover->get_sol_numbers();
		if (cDebug::is_debugging())
			foreach ($aSols as $sSol)
				echo "<a href='?s=$sSol&debug'>$sSol</a> ";
		else
			cCommon::write_json($aSols);
	}else{
		cDebug::write("looking for sol $sSol");
		$sInstr = cHeader::get("i");
		if ($sInstr == null){
			$oSol = $oRover->get_sol($sSol);
			$aInstruments = $oSol->instruments;
			if (cDebug::is_debugging()){
				echo "<ul>";
				foreach ($aInstruments as $sAbbr=>$oInstrument){
					$aInfo = $oInstruments->getDetails($sAbbr);
					$sCaption  = $aInfo["caption"];
					echo "<li><a href='?s=$sSol&i=$sAbbr&debug'> $sCaption ($oInstrument->count)";
				}
				echo "</ul>";
			}
			else
				cCommon::write_json($aInstruments);
		}else{
			cDebug::write("looking for instrument $sInstr");
			$aDetails = $oRover->get_details($sSol, $sInstr);
			if (cDebug::is_debugging())
				cCommon::write_json($aDetails);
			else
				cCommon::write_json($aDetails);
		}
	}
	
?>

