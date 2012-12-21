var pubmed_author_search = "http://localhost/PubMed/json.json";


$('submiter').observe("click", function(event) {
	console.log($('Author').value);
	
	var request = new Ajax.Request(pubmed_author_search, {
		onSuccess: function(response) {
			console.log("success", response);
			
			display_coauthors(response.responseText.evalJSON());
		}
	});
	
});

function display_coauthors(json) {
	console.log(json);
	json = json.coauthors;
	//var template = new Template('<div><span>#{name}</span><span>#{metric}</span><div>');
	json.each( function(author) { 
		console.log(author);
		//var div = template.evalute(author);
		var div = "<div><span>"+author.name+"</span><span>"+author.metric+"</span></div>";
		console.log(div);
		$('coauthors').insert(div);	
	});
}
