<style type="text/css">

.picture-circle{
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    border-radius: 50%;
    width: 68px;
    height: 68px;
}

.picture-all-bg{
	background-position: center center;
	background-repeat: no-repeat;
    background-size: cover; height: 300px;
    text-align: center;
}

table {
    border-collapse: collapse;
    width: 100%;
}

table, th, td {
    border: 1px solid black;
}

th, td {
    padding: 10px;
    text-align: left;
}

th{
	background-color: #84C567;
}
</style>

<div class="" style="text-align: center;">
	<img src="https://s3.amazonaws.com/isaudavel-assets/logos/i_saudavel-LOGO-01.png" width="200" alt="Logo" border="0" style="text-align: center;">
</div>
<h3 style="text-align: center;">Ficha de treinamento</h3>

<h4>Criado por</h4>
<div class="picture-circle" style="background-image: url('{{$trainning->from->avatar}}');"></div>
<p>{{$trainning->from->full_name}}</p>

<h4>Series</h4>
{{ strip_tags($trainning->series) }}


