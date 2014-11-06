<html> 
<body onload="document.forms.primo.query.value=null"> 
<script> 
        function searchPrimo() {
          document.getElementById("primoQuery").value = "any,contains," + document.getElementById("primoQueryTemp").value;
          document.forms["primoSearchForm"].submit(); 
         }

</script> <p><b> my Deep Link Search Form</b> 
      <form name="primoSearchForm" method="post" action="http://primo-standard-lb.hosted.exlibrisgroup.com/primo_library/libweb/action/dlSearch.do?" enctype="application/x-www-form-urlencoded; charset=utf-8" class="form-inline" id="discovery-search">
        <input type="hidden" id="institution" name="institution" value="CSUSM"/> 
        <input type="hidden" id="vid" name="vid" value="CSUSM"/> 
           <input type="hidden" name="group" value="GUEST" />
           <input type="hidden" name="mode" value="Basic" />
           <input type="hidden" name="onCampus" value="true" />
           <input type="hidden" name="displayMode" value="full" />
           <input type="hidden" name="prefLang" value="en_US" />
           <input type="hidden" name="search_scope" value="default" />
           <input type="hidden" id="primoQuery" name="query" />
        <input type="hidden" id="indx" name="indx" value="1"/> 
        <input type="hidden" id="bulkSize" name="bulkSize" value="10"/> 
        <input type="hidden" id="dym" name="dym" value="true"/> 
        <input type="hidden" id="highlight" name="highlight" value="true"/> 
        <input type="hidden" id="displayField" name="displayField" value="title"/> 
        <input type="text" size="50" title="search_field" id="primoQueryTemp" name="queryTemp" class="span8" value="" /> 
        <input type="button" title="Search" class="btn btn-info" id="goButton" value="Search" name="submit" onclick="searchPrimo()"/> 
      </form>



</body> 
</html>