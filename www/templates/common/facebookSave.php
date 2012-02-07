<script>
<?if($result["caller"] == "MAIN"){?>
opener.location.href="/timeline.php";
<?}elseif($result["caller"] == "TIMELINE"){?>
opener.document.getElementById("facebook").checked = true;
<?}?>
self.close();
</script>
