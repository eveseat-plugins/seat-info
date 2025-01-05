<div class="form-group">
  <label for="name">{{ trans('info::info.access_management_label') }}</label>
  <ul class="list-group mt-2" id="acl-role-list">
    <li class="list-group-item p-0-75">
      <div class="d-flex flex-row">
        <select class="form-control mr-1" id="role-selector">
          @foreach($roles as $role)
            <option value="{{ $role->id }}">{{ $role->title }}</option>
          @endforeach
        </select>
        <select class="form-control mr-1" style="max-width: 150px" id="permission-level-selector">
          <option value="view">{{ trans('info::info.view') }}</option>
          <option value="edit">{{ trans('info::info.edit') }}</option>
        </select>
        <button type="button" class="btn btn-success" id="add-acl-role" style="min-width: 80px">{{ trans('info::info.add_role') }}</button>
      </div>
    </li>
    <li class="list-group-item p-0-75 text-muted" id="acl-role-list-empty-info">
      {{ trans("info::info.acl_no_roles_configured") }}
    </li>
  </ul>
  <small class="text-muted">
    {{ trans("info::info.edit_article_acl_help") }}
  </small>

  <input type="hidden" name="aclRoleData" value="[]" id="acl-role-data">
</div>

@push('javascript')
  <script>
      function addAclRoleEntry(roleID,roleName, permissionLevel) {
          const roleList = document.querySelector("#acl-role-list")
          if(roleList.querySelector(`li[data-role-id="${roleID}"]`)) return // role already added
          roleList.insertAdjacentHTML("beforeend", `<li class="list-group-item d-flex flex-row align-items-baseline p-0-75" data-role-id="${roleID}">
                                                                       ${roleName}
                                                                       <select class="form-control ml-auto" style="max-width:100px">
                                                                           <option value="view" ${permissionLevel=="view"?"selected=\"selected\"":""}>{{ trans('info::info.view') }}</option>
                                                                           <option value="edit" ${permissionLevel=="edit"?"selected=\"selected\"":""}>{{ trans('info::info.edit') }}</option>
                                                                       </select>
                                                                       <button class="btn ml-0-75" type="button" id="remove-role-${roleID}">
                                                                           <i class="fas fa-trash text-danger"></i>
                                                                       </button>
                                                                   </li>`)

          document.querySelector("#acl-role-list-empty-info").style.display="none"

          document.querySelector(`#remove-role-${roleID}`).addEventListener("click",function () {
              roleList.querySelector(`li[data-role-id="${roleID}"]`).remove()
              if (roleList.querySelectorAll("li[data-role-id]").length === 0){
                  document.querySelector("#acl-role-list-empty-info").style.display=""
              }
          })
      }

      document.querySelector("#add-acl-role").addEventListener("click",function (e) {
          this.blur()

          const roleSelector = document.querySelector("#role-selector")
          const roleID = roleSelector.value
          const roleName = roleSelector.options[roleSelector.selectedIndex].text;
          const permissionLevel = document.querySelector("#permission-level-selector").value

          addAclRoleEntry(roleID, roleName, permissionLevel)
      })

      document.querySelector(".acl-editor-submit-form").addEventListener("submit",function (e){
          const roleListEntryLiList = document.querySelector("#acl-role-list").querySelectorAll("li[data-role-id]")
          const data = []
          for (const roleListEntryLi of roleListEntryLiList) {
              data.push({
                  roleID: roleListEntryLi.dataset.roleId,
                  state: roleListEntryLi.querySelector("select").value
              })
          }
          document.querySelector("#acl-role-data").value = JSON.stringify(data)
      })

      @foreach($acl_roles as $acl_role)
      addAclRoleEntry("{{ $acl_role->role }}","{{ $acl_role->roleModel->title }}","{{ $acl_role->allows_edit?"edit":($acl_role->allows_view?"view":"") }}")
    @endforeach
  </script>
@endpush