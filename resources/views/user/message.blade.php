@extends('layout.userCommon')
@section('content')
		<div class="m_right">
            <p></p>
            <div class="mem_tit">我的留言</div>
           	<form>
            <table border="0" style="width:880px; margin-top:20px;"  cellspacing="0" cellpadding="0">
              <tr height="45">
                <td width="80" align="right">留言类型 &nbsp; &nbsp;</td>
                <td>                            
                	<label class="r_rad"><input type="checkbox" name="type" checked="checked" /></label><label class="r_txt">留言</label>
                    <label class="r_rad"><input type="checkbox" name="type" /></label><label class="r_txt">投诉</label>
                    <label class="r_rad"><input type="checkbox" name="type" /></label><label class="r_txt">询问</label>
                    <label class="r_rad"><input type="checkbox" name="type" /></label><label class="r_txt">售后</label>
                    <label class="r_rad"><input type="checkbox" name="type" /></label><label class="r_txt">求购</label>
                </td>
              </tr>
              <tr height="45">
                <td align="right">主题 &nbsp; &nbsp;</td>
                <td><input type="text" value="" class="add_ipt" style="width:290px;" /></td>
              </tr>
              <tr valign="top" height="110">
                <td align="right">留言内容 &nbsp; &nbsp;</td>
                <td style="padding-top:5px;"><textarea class="add_txt"></textarea></td>
              </tr>
              <tr height="45">
                <td align="right">上传文件 &nbsp; &nbsp;</td>
                <td><input type="file" /></td>
              </tr>
              <tr height="50">
                <td>&nbsp;</td>
                <td style="line-height:20px;">
                	<font color="#ff4e00">小提示：</font><br />
                    您可以上传以下格式的文件：gif、jpg、png、word、excel、txt、zip、ppt、pdf
                </td>
              </tr>
              <tr height="50" valign="bottom">
                <td>&nbsp;</td>
                <td><input type="submit" value="提交" class="btn_tj" /></td>
              </tr>
            </table>
            </form>
         
        </div>
	<!--End 用户中心 End--> 
@endsection