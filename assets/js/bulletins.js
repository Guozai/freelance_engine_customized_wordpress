jQuery(function($) {
    $("div[id^=bulletin-list-wrap]").click(function() {
        var startPos = "bulletin-list-wrap-".length;
        var endPos = this.id.length;
        var id = this.id.substring(startPos, endPos);
		$("#bulletin-content-wrap-" + id).toggle('slow');
	});
});