<div class="mb-3">
    <label for="" class="form-label">访问地址,结尾不要加/</label>
    <input class="form-control" type="text" name="ftp[url]" value="{{get_options('upload_ftp_url')}}">
</div>

<div class="mb-3">
    <label for="" class="form-label">FTP 主机名</label>
    <input class="form-control" type="text" name="ftp[host]" value="{{get_options('upload_ftp_host')}}">
    <small>IP或者域名</small>
</div>

<div class="mb-3">
    <label for="" class="form-label">FTP 端口号</label>
    <input class="form-control" type="number" name="ftp[port]" value="{{get_options('upload_ftp_port')}}">
</div>

<div class="mb-3">
    <label for="" class="form-label">SSL 连接</label>
    <select name="ftp[ssl]" class="form-select">
        <option value="关闭">关闭</option>
        <option value="开启" @if(get_options('upload_ftp_ssl','关闭')==="开启") selected @endif>开启</option>
    </select>
</div>

<div class="mb-3">
    <label for="" class="form-label">FTP 用户名</label>
    <input class="form-control" type="text" name="ftp[username]" value="{{get_options('upload_ftp_username')}}">
</div>

<div class="mb-3">
    <label for="" class="form-label">FTP 密码</label>
    <input class="form-control" type="password" name="ftp[password]" value="{{get_options('upload_ftp_password')}}">
</div>

<div class="mb-3">
    <label for="" class="form-label">FTP 保存目录</label>
    <input class="form-control" type="text" name="ftp[dir]" value="{{get_options('upload_ftp_dir','upload')}}">
</div>
