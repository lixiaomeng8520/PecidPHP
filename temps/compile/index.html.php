<html>
<head>
	<title></title>
</head>
<body>
<?php foreach($this->_var['list1'] as $this->_var['k'] => $this->_var['v']):?>
<?php echo $this->_var['v']['username']; ?>:<?php echo $this->_var['v']['age']; ?><br>
<?php endforeach;?>

<br>
<?php foreach($this->_var['list2'] as $this->_var['k'] => $this->_var['v']):?>
<?php echo $this->_var['v']['username']; ?>:<?php echo $this->_var['v']['age']; ?><br>
<?php endforeach;?>

<?php echo $_GET['flag']; ?>

</body>
</html>

