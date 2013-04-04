<?php  
  require_once(dirname(__FILE__).'/src/common.inc.php');
?><html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>Mercurial repositories</title>
	<link rel=stylesheet type="text/css" href="media/main.css">
</head>
<body>
  <div id="wraper">
    <!-- projects -->
    <table id="projects_table">
      <tbody>
        <tr>
          <th>Working copy</th>
          <th>Browse repo</th>
          <th>Actions</th>
        </tr>
      </tbody>
    </table>
  </div>

  <div id="modal_overlay">
    <div id="actions"><a href="javascript:" onclick="MainUtils.getModalOverlay().close(); return false;"><b>close X</b></a></div>
    <div class="content"></div>
  </div>
  <script type="text/javascript" src="media/jquery.js" ></script> 
  <script type="text/javascript" src="media/main.js" ></script>
  <script type="text/javascript">
    jQuery(document).ready(function() {
      Merman.init();
      var projects = <?php echo MerProject :: findAll(true); ?>;
      Merman.displayProjects(projects);
    });
  </script>
  <!-- Templates -->
  <table>
  <tbody class="template" id="tpl_project_item">    
    <tr id="p_{name}">
      <td><a id="wc">{name}</a></td>
      <td><a id="repository">repository</a></td>
      <td>
        <a class="up" href="javascript:" onclick="Merman.a.up('{name}')">Update</a>
        <a href="javascript:" onclick="Merman.a.clone('{name}')">Clone</a>
        <a class="move" href="javascript:" onclick="Merman.a.move('{name}')">Move</a>
        <a class="delete" href="javascript:" onclick="Merman.a.deleteProject('{name}')">Delete</a>
      </td>
    </tr>
  </tbody>
  </table>

  <div class="template" id="tpl_clone_form">
    <strong>Clone project '{name}'</strong>
    <form>
      <input type="hidden" name="source" value="{name}" />
      <dl>
        <dt>Folder:</dt>
        <dd><input name="folder" type="text" size="30" /></dd>
      </dt>
      <dl>
        <dt></dt>
        <dd><input type="submit" value="Clone"  /></dd>
      </dt>
    </form>
  </div>
  
  <div class="template" id="tpl_move_form">
    <strong>Move project '{name}'</strong>
    <form>
      <input type="hidden" name="source" value="{name}" />
      <dl>
        <dt>New name:</dt>
        <dd><input name="new_name" type="text" size="30" /></dd>
      </dt>
      <dl>
        <dt></dt>
        <dd><input type="submit" value="Move"  /></dd>
      </dt>
    </form>
  </div>
</body>
</html>
