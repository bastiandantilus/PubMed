<?php
/**
 * Returns a json encoded array of co authors for a given author's name
 *
 */
$author = isset($_REQUEST['author']) ? $_REQUEST['author'] : false;
$author_name = explode(" ", $author);
$author_name = $author_name[count($author_name)-1].", ".substr($author_name[0], 0, 1);
$authors = array();

function getCanonicalAuthorName($author) {
	return $author->LastName .", ". substr($author->ForeName, 0, 1);
}

if ($xml = simplexml_load_file('http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&term='.urlencode($author).'&retmax=50' )) {
	if($xml) {
		$ids = $xml->IdList->children(); 
		//print_r($ids->asXML());
		foreach($ids as $i => $id) {
			//print($id->asXML());
			if($xml = simplexml_load_file('http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&id='.$id.'&rettype=xml')) {
				//print($xml->PubmedArticle->MedlineCitation->asXML());
				$n = 10;
				foreach($xml->PubmedArticle->MedlineCitation->Article->AuthorList->children() as $i=> $author) {
					$name = getCanonicalAuthorName($author);
					if($name == $author_name) { continue; }
					$authors[$name] = isset($authors[$name]) ? $authors[$name] + $n : $n;
					if($n-- <= 1) { break; }
				}
				//print(json_encode($authors));
			}
		}		
	}
}

print(json_encode($authors));


