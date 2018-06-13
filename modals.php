<?php
/**
 * Created by PhpStorm.
 * User: Bartosz
 * Date: 12.06.2018
 * Time: 21:51
 */

include "php/config.php";

$pm = new PersonManagment();

if(isset($_GET['edit'])):
	$person = $pm->SelectPerson($_GET['edit']);
	print_r($person);
	?>



	<div id="modal_person" class="modal-content  main-color-gradient p-3">
		<div class="modal-header">
			<h6 class="modal-title text-center w-100" >Edit person</h6>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>

		</div>
		<form method="POST" onsubmit="return EditPerson(this)"  action="index.php"  >
			<div class="alert alert-danger" style="display: none">

			</div>
			<input type="hidden" name="action" value="editperson" />
			<input type="hidden" name="id" value="<?php echo $_GET['edit'] ?>">
			<div class="bg-white pd-nm border-main-col-dark small">
				<div class="form-group row">
					<label  class="col-sm-3 col-form-label col-form-label-sm">Name</label>
					<div class="col-sm-9">
						<input  type="text" class="form-control form-control-sm" name="name" value="<?php echo $person->name; ?>"  placeholder="person name" required>

					</div>
				</div>
				<div class="form-group row">
					<label  class="col-sm-3 col-form-label col-form-label-sm">Address</label>
					<div class="col-sm-9">
						<textarea  type="text" class="form-control form-control-sm" name="address"  placeholder="person address" required><?php echo $person->address; ?></textarea>

					</div>
				</div>
				<div class="form-group row">
					<label  class="col-sm-3 col-form-label col-form-label-sm">Birth date: </label>
					<div class="col-sm-6 position-relative" >
						<input type="date"  class="form-control form-control-sm " value="<?php echo $person->GetBirthDate(); ?>"  name="birth_date" required/>
					</div>
				</div>
				<div class="form-group row">
					<label  class="col-sm-3 col-form-label col-form-label-sm">Color: </label>
					<div class="col-sm-6 position-relative" >
						<input type="color"  class="form-control form-control-sm " value="<?php echo $person->personData->color; ?>"  name="color" />
					</div>
				</div>
				<div class="form-group row">
					<label  class="col-sm-3 col-form-label col-form-label-sm">Website: </label>
					<div class="col-sm-6 position-relative" >
						<input type="text"  class="form-control form-control-sm " value="<?php echo $person->personData->website; ?>"  name="website" />
					</div>
				</div>
			</div>
			<div class="pd-sm border-main-col-dark main-color-gradient">
				<div class="row">
					<div class="col-sm-6">
						<button type="button" class="btn btn-outline-primary btn-alfa" data-dismiss="modal" aria-label="Close" ><i class="fas fa-ban text-danger"></i> Close</button>
					</div>
					<div class="col-sm-6">
						<button type="submit" class="btn btn-outline-primary btn-alfa float-right"><i class="fas fa-plus text-success"></i> Save</button>
					</div>
				</div>

			</div>
		</form>
	</div>



<?php  elseif(isset($_GET['add'])) : ?>

	<div id="modal_person" class="modal-content  main-color-gradient p-3">
		<div class="modal-header">
			<h6 class="modal-title text-center w-100" >Add new person</h6>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>

		</div>
		<form method="POST" onsubmit="return AddPerson(this)"  action="index.php"  >
			<div class="alert alert-danger" style="display: none">

			</div>
			<input type="hidden" name="action" value="addperson" />
			<div class="bg-white pd-nm border-main-col-dark small">
				<div class="form-group row">
					<label  class="col-sm-3 col-form-label col-form-label-sm">Name</label>
					<div class="col-sm-9">
						<input  type="text" class="form-control form-control-sm" name="name"  placeholder="person name" required>

					</div>
				</div>
				<div class="form-group row">
					<label  class="col-sm-3 col-form-label col-form-label-sm">Address</label>
					<div class="col-sm-9">
						<textarea  type="text" class="form-control form-control-sm" name="address"  placeholder="person address" required></textarea>

					</div>
				</div>
				<div class="form-group row">
					<label  class="col-sm-3 col-form-label col-form-label-sm">Birth date: </label>
					<div class="col-sm-6 position-relative" >
						<input type="date"  class="form-control form-control-sm " value=""  name="birth_date" required/>
					</div>
				</div>
				<div class="form-group row">
					<label  class="col-sm-3 col-form-label col-form-label-sm">Color: </label>
					<div class="col-sm-6 position-relative" >
						<input type="color"  class="form-control form-control-sm " value=""  name="color" />
					</div>
				</div>
				<div class="form-group row">
					<label  class="col-sm-3 col-form-label col-form-label-sm">Website: </label>
					<div class="col-sm-6 position-relative" >
						<input type="url"  class="form-control form-control-sm " value=""  name="website" />
					</div>
				</div>
			</div>
			<div class="pd-sm border-main-col-dark main-color-gradient">
				<div class="row">
					<div class="col-sm-6">
						<button type="button" class="btn btn-outline-primary btn-alfa" data-dismiss="modal" aria-label="Close" ><i class="fas fa-ban text-danger"></i> Close</button>
					</div>
					<div class="col-sm-6">
						<button type="submit" class="btn btn-outline-primary btn-alfa float-right"><i class="fas fa-plus text-success"></i> Add</button>
					</div>
				</div>

			</div>
		</form>
	</div>



<?php endif;
