@extends('layouts.master')
@section('title', 'Tambah Stok')
@section('content')
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>
                    Home</a> <a href="#" class="tip-bottom">Form elements</a> <a href="#" class="current">Common
                    elements</a>
            </div>
            <h1>Common Form Elements</h1>
        </div>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                            <h5>Personal-info</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <form action="#" method="get" class="form-horizontal">
                                <div class="control-group">
                                    <label class="control-label">Nama Jenis Gas :</label>
                                    <div class="controls">
                                        <input type="text" name="nama_gas" class="span11" placeholder="Masukkan Nama Jenis Gas" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Jumlah Tabung Penuh :</label>
                                    <div class="controls">
                                        <input type="number" name="jumlah_tabung_penuh" class="span11" placeholder="Masukkan Jumlah Tabung Penuh" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Password input</label>
                                    <div class="controls">
                                        <input type="password" class="span11" placeholder="Enter Password" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Company info :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Company name" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Tanggal:</label>
                                    <div class="controls">
                                        <input type="date" class="span11" />
                                        <span class="help-block">Description field</span>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Message :</label>
                                    <div class="controls">
                                        <textarea class="span11"></textarea>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">File upload input :</label>
                                    <div class="controls">
                                        <input type="file" />
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-success">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="span6">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                            <h5>Form Elements</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <form action="#" method="get" class="form-horizontal">
                                <div class="control-group">
                                    <label class="control-label">Select input</label>
                                    <div class="controls">
                                        <select>
                                            <option>First option</option>
                                            <option>Second option</option>
                                            <option>Third option</option>
                                            <option>Fourth option</option>
                                            <option>Fifth option</option>
                                            <option>Sixth option</option>
                                            <option>Seventh option</option>
                                            <option>Eighth option</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Multiple Select input</label>
                                    <div class="controls">
                                        <select multiple>
                                            <option>First option</option>
                                            <option selected>Second option</option>
                                            <option>Third option</option>
                                            <option>Fourth option</option>
                                            <option>Fifth option</option>
                                            <option>Sixth option</option>
                                            <option>Seventh option</option>
                                            <option>Eighth option</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Radio inputs</label>
                                    <div class="controls">
                                        <label>
                                            <input type="radio" name="radios" />
                                            First One</label>
                                        <label>
                                            <input type="radio" name="radios" />
                                            Second One</label>
                                        <label>
                                            <input type="radio" name="radios" />
                                            Third One</label>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Checkboxes</label>
                                    <div class="controls">
                                        <label>
                                            <input type="checkbox" name="radios" />
                                            First One</label>
                                        <label>
                                            <input type="checkbox" name="radios" />
                                            Second One</label>
                                        <label>
                                            <input type="checkbox" name="radios" />
                                            Third One</label>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Disabled Input</label>
                                    <div class="controls">
                                        <input type="text" placeholder="You can't type anything…" disabled=""
                                            class="span11">
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-success">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row-fluid">
                <div class="span6">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                            <h5>Form Elements</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <form class="form-horizontal">
                                <div class="control-group">
                                    <label class="control-label">Color picker (hex)</label>
                                    <div class="controls">
                                        <input type="text" data-color="#ffffff" value="#ffffff"
                                            class="colorpicker input-big span11">
                                        <span class="help-block">Color picker with Formate of (hex)</span>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Color picker (rgba)</label>
                                    <div class="controls">
                                        <input type="text" data-color="rgba(344,232,53,0.5)"
                                            value="rgba(344,232,53,0.5)" data-color-format="rgba"
                                            class="colorpicker span11">
                                        <span class="help-block">Color picker with Formate of (rgba)</span>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Date picker (dd-mm)</label>
                                    <div class="controls">
                                        <input type="text" data-date="01-02-2013" data-date-format="dd-mm-yyyy"
                                            value="01-02-2013" class="datepicker span11">
                                        <span class="help-block">Date with Formate of (dd-mm-yy)</span>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Date Picker (mm-dd)</label>
                                    <div class="controls">
                                        <div data-date="12-02-2012" class="input-append date datepicker">
                                            <input type="text" value="12-02-2012" data-date-format="mm-dd-yyyy"
                                                class="span11">
                                            <span class="add-on"><i class="icon-th"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Color Picker (rgb)</label>
                                    <div class="controls">
                                        <div data-color-format="rgb" data-color="rgb(155, 142, 180)"
                                            class="input-append color colorpicker colorpicker-rgb">
                                            <input type="text" value="rgb(155, 142, 180)" class="span11">
                                            <span class="add-on"><i
                                                    style="background-color: rgb(155, 142, 180)"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Color Picker (hex)</label>
                                    <div class="controls">
                                        <div data-color-format="hex" data-color="#000000"
                                            class="input-append color colorpicker">
                                            <input type="text" value="#000000" class="span11">
                                            <span class="add-on"><i style="background-color: #000000"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <button type="submit" class="btn btn-primary">Reset</button>
                                    <button type="submit" class="btn btn-info">Edit</button>
                                    <button type="submit" class="btn btn-danger">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="span6">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                            <h5>Form Elements</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <form class="form-horizontal">
                                <div class="control-group">
                                    <label class="control-label">Tooltip Input</label>
                                    <div class="controls">
                                        <input type="text" placeholder="Hover for tooltip…"
                                            data-title="A tooltip for the input" class="span11 tip"
                                            data-original-title="">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Type ahead Input</label>
                                    <div class="controls">
                                        <input type="text" placeholder="Type here for auto complete…"
                                            style="margin: 0 auto;" data-provide="typeahead" data-items="4"
                                            data-source="[&quot;Alabama&quot;,&quot;Alaska&quot;,&quot;Arizona&quot;,&quot;Arkansas&quot;,&quot;California&quot;,&quot;Colorado&quot;,&quot;Ahmedabad&quot;,&quot;India&quot;,&quot;Florida&quot;,&quot;Georgia&quot;,&quot;Hawaii&quot;,&quot;Idaho&quot;,&quot;Illinois&quot;,&quot;Indiana&quot;,&quot;Iowa&quot;,&quot;Kansas&quot;,&quot;Kentucky&quot;,&quot;Louisiana&quot;,&quot;Maine&quot;,&quot;Maryland&quot;,&quot;Massachusetts&quot;,&quot;Michigan&quot;,&quot;Minnesota&quot;,&quot;Mississippi&quot;,&quot;Missouri&quot;,&quot;Montana&quot;,&quot;Nebraska&quot;,&quot;Nevada&quot;,&quot;New Hampshire&quot;,&quot;New Jersey&quot;,&quot;New Mexico&quot;,&quot;New York&quot;,&quot;North Dakota&quot;,&quot;North Carolina&quot;,&quot;Ohio&quot;,&quot;Oklahoma&quot;,&quot;Oregon&quot;,&quot;Pennsylvania&quot;,&quot;Rhode Island&quot;,&quot;South Carolina&quot;,&quot;South Dakota&quot;,&quot;Tennessee&quot;,&quot;Texas&quot;,&quot;Utah&quot;,&quot;Vermont&quot;,&quot;Virginia&quot;,&quot;Washington&quot;,&quot;West Virginia&quot;,&quot;Wisconsin&quot;,&quot;Wyoming&quot;]"
                                            class="span11">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Prepended Input</label>
                                    <div class="controls">
                                        <div class="input-prepend"> <span class="add-on">#</span>
                                            <input type="text" placeholder="prepend" class="span11">
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Appended Input</label>
                                    <div class="controls">
                                        <div class="input-append">
                                            <input type="text" placeholder="5.000" class="span11">
                                            <span class="add-on">$</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group warning">
                                    <label class="control-label" for="inputWarning">Input with warning</label>
                                    <div class="controls">
                                        <input type="text" id="inputWarning" class="span11">
                                        <span class="help-inline">Something may have gone wrong</span>
                                    </div>
                                </div>
                                <div class="control-group error">
                                    <label class="control-label" for="inputError">Input with error</label>
                                    <div class="controls">
                                        <input type="text" id="inputError" class="span11">
                                        <span class="help-inline">Please correct the error</span>
                                    </div>
                                </div>
                                <div class="control-group info">
                                    <label class="control-label" for="inputInfo">Input with info</label>
                                    <div class="controls">
                                        <input type="text" id="inputInfo" class="span11">
                                        <span class="help-inline">Username is already taken</span>
                                    </div>
                                </div>
                                <div class="control-group success">
                                    <label class="control-label" for="inputSuccess">Input with success</label>
                                    <div class="controls">
                                        <input type="text" id="inputSuccess" class="span11">
                                        <span class="help-inline">Woohoo!</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
