//var pubmed_author_search = "http://localhost/PubMed/json.json";
var pubmed_author_search = "http://localhost/PubMed/pubmed.php";

$('submiter').observe("click", function(event) {
	//console.log($('Author').value);
	$('submiter').disabled = "disabled";
	
	var request = new Ajax.Request(pubmed_author_search, {
		parameters: { author : $('Author').value },
		onSuccess: function(response) {
			//console.log("success", response);
			
			display_coauthors(response.responseText.evalJSON());
			$('submiter').disabled = false;
		},
	});
	
});

function display_coauthors(json) {
	//console.log(json);
	authors = Object.keys(json);
	authors = authors.sortBy(function(author) { return json[author]; }).reverse().slice(0,10);
	authors.each( function(author) { 
		//console.log(author);
		//var div = template.evalute(author);
		var div = "<div><span>"+author+"</span><span>"+json[author]+"</span></div>";
		//console.log(div);
		$('coauthors').insert(div);	
	});
}
