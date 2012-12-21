<?php

$author = isset($_REQUEST['author']) ? $_REQUEST['author'] : false;
$authors = array();

function getCanonicalAuthorName($author) {
	return $author->LastName .", ". substr($author->ForeName, 0, 1);
}

if ($xml = simplexml_load_file('http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&term='.urlencode($author).'&retmax=100000' )) {
	if($xml) {
		$ids = $xml->IdList->children(); 
		//print_r($ids->asXML());
		foreach($ids as $i => $id) {
			//print($id->asXML());
			if($xml = simplexml_load_file('http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&id='.$id.'&rettype=xml')) {
				//print($xml->PubmedArticle->MedlineCitation->asXML());
				$n = 10;
				foreach($xml->PubmedArticle->MedlineCitation->Article->AuthorList->children() as $i=> $author) {
					$n--;
					$name = getCanonicalAuthorName($author);
					$authors[$name] = isset($authors[$name]) ? $authors[$name] + $n : $n;
					if($n-- < 1) { break; }
				}
				//print(json_encode($authors));
			}
		}		
	}
}

print(json_encode($authors));


