<script>
<?if($result["caller"] == "MAIN"){?>
opener.location.href="/timeline.php";
<?}elseif($result["caller"] == "TIMELINE"){?>
opener.document.getElementById("twitter").checked = true;
<?}?>
opener.TIMELINE.EXTERNAL.info.TWITTER = {};
self.close();
</script>
