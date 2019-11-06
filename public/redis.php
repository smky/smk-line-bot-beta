<HEAD>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-2338419-4"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-2338419-4');
</script>

</HEAD>
<?php
	
function getRedis($keyword){	
	$path = __DIR__ . '/../vendor/predis/predis/autoload.php';
	require_once $path;
	$redis = new Predis\Client(getenv('REDIS_URL'));
	$value = $redis->lrange("response:$keyword", 0, -1);
	return $value;
	
}

function remRedis($keyword, $count, $response){	
	$path = __DIR__ . '/../vendor/predis/predis/autoload.php';
	require_once $path;
	$redis = new Predis\Client(getenv('REDIS_URL'));
	$value = $redis->lrem("response:$keyword", $count ,$response);
	return $value;
	
}

function saveRedis($keyword, $response, $password){	
	$path = __DIR__ . '/../vendor/predis/predis/autoload.php';
	require_once $path;
	$redis = new Predis\Client(getenv('REDIS_URL'));
	if ($password == 'wordpass')
	{
	$value = $redis->lpush("response:$keyword", $response);	
	}
	
	return $value;
	
}


function delRedis($keyword, $response){	
	$path = __DIR__ . '/../vendor/predis/predis/autoload.php';
	require_once $path;
	$redis = new Predis\Client(getenv('REDIS_URL'));
	$value = $redis->del("response:$keyword");
	return $value;
	
}

?>
		<form method="post" action=redis.php>
		<p>Keyword:<input type="text" name="Keyword" /></p>		
		<p>Response: <input type="text" name="Response" /></p>
		<p>Count: <input type="text" name="Count" /></p>
				
			<select name="Function">
				<option value="getRedis">getRedis</option>
				<option value="remRedis">remRedis</option>
				<option value="saveRedis">saveRedis</option>
			</select>
		<input type="submit" name="submit" value="Submit" />
		</form>
		
		
		<figure>
			<figcaption><? echo $_POST['Keyword']; ?></figcaption>
			<pre>
			<code contenteditable spellcheck="false">
			<!-- your code here -->
<?
if (!empty($_POST['Keyword']))
	{
	if ($_POST['Function'] == 'getRedis')
		{
		$Response = getRedis($_POST['Keyword']);
		print_r ($Response);
		}
		
	if ($_POST['Function'] == 'remRedis')
		{
		remRedis($_POST['Keyword'],$_POST['Count'],$_POST['Response']);
		$Response = getRedis($_POST['Keyword']);
		print_r ($Response);
		}	
		
	if ($_POST['Function'] == 'saveRedis')
		{
		$Response = getRedis($_POST['Keyword']);
				foreach ($Response as $data)
				{
					if ($data == $_POST['Response'])
					{
						echo 'You allready register '.$_POST['Response'].'<br> ';
						$datasaved = '1';
					}
				}
		if ($datasaved != '1')
		{
			saveRedis ($_POST['Keyword'],$_POST['Response'] ,$_POST['Count']);
		}
		
		$Response = getRedis($_POST['Keyword']);
		print_r ($Response);
		}	
		
	
	
	}

?>
			</code>
			</pre>
		</figure>
