
    $(document).ready(function() {
        var fieldIndex = 1;
        var appUrl = $('#appUrl').val();
        $('.deleteModuleBtn').on('click', function() {
            var moduleId = $(this).data('id');
            if (confirm('Are you sure you want to delete this module?')) {
                $.ajax({
                    url: appUrl + '/api/advance-module/' + moduleId +
                        '/delete', // Adjust the URL based on your route
                    type: 'DELETE',
                    success: function(response) {
                        location.reload();
                    }
                })
            }
        })
        // edit funtionallty
        $('.editModuleBtn').on('click', function() {
            var moduleId = $(this).data('id');
            $(".updateBtn").show();
            $(".submitBtn").hide();
            $("#create-connection-btn").hide();
            $('#db_type').on('mousedown', function(e) {
        e.preventDefault(); // Prevent the dropdown from opening
    }).css({
        'background-color': '#f8f9fa', // Light gray background
        'color': '#6c757d', // Gray text
        'pointer-events': 'none' // Disable pointer events
    });
    $('#module_name').prop('readonly', true);
            $('#db_type').prop('readonly', true);
            $('#db_host').prop('readonly', true);
            $('#db_port').prop('readonly', true);
            $('#db_name').prop('readonly', true);
            $('#db_user').prop('readonly', true);
            $('#db_password').prop('readonly', true);
            // Make AJAX request to fetch the module data
            $.ajax({
                url: appUrl + '/api/advance-module/' + moduleId +
                '/edit', // Adjust the URL based on your route
                type: 'GET',
                success: function(response) {
                    // Populate the modal form with module data
                    $('#module_id').val(response.id);
                    $('#module_name').val(response.name);
                    $('.current_status').val(response.status);

                    // Enable Database Connection checkbox
                    if (response.db_conn_status) {
                        $('#enable-db-connection').prop('checked', true);
                        $('#db-connection-fields').show();
                        $('#db_type').val(response.db_type);
                        $('#db_host').val(response.db_host);
                        $('#db_port').val(response.db_port);
                        $('#db_name').val(response.db_name);
                        $('#db_user').val(response.db_username);
                        $('#db_password').val(response.db_password);
                    } else {
                        $('#enable-db-connection').prop('checked', false);
                        $('#db-connection-fields').hide();
                    }

                    // Set role permissions checkboxes
                    console.log(response, "response");
                    $('input[name="role_permissions[]"]').each(function() {
                        $(this).prop('checked', response.role_permissions.includes(
                            $(this).val()));
                    });

                    // Populate dynamic fields
                    $('#dynamic-field-container').empty(); // Clear existing dynamic fields
                    response.datatable_details.forEach(function(field, index) {
                        console.log(index, "index")
                        fieldIndex = index + 1;
                        // Clone your row structure here and fill with data
                        let fieldRow = `
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Attribute ${index + 1}</label>
                        <input type="text" class="form-control" name="fields[${index}][attribute]" value="${field.attribute}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Type</label>
                        <select class="form-control" name="fields[${index}][type]">
                            <option value="${field.type}" selected>${field.type.toUpperCase()}</option>
                            <!-- Add other options here as needed -->
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Length</label>
                        <input type="number" class="form-control" name="fields[${index}][length]" value="${field.length}">
                    </div>
                    <div class="col-md-3 d-flex align-items-center">
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" name="fields[${index}][nullable]" ${field.nullable ? 'checked' : ''}>
                            <label class="form-check-label">Nullable</label>
                        </div>
                    </div>
                </div>`;
                        $('#dynamic-field-container').append(fieldRow);
                    });

                    // Show the modal
                    $('#myModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching module data:", error);
                }
            });

        });



        var loader = $('#loader');

        var mysqlOptions = `
            <option value="bigint">BIGINT</option>
            <option value="bit">BIT</option>
            <option value="boolean">BOOLEAN</option>
            <option value="char">CHAR</option>
            <option value="date">DATE</option>
            <option value="datetime">DATETIME</option>
            <option value="decimal">DECIMAL</option>
            <option value="double">DOUBLE</option>
            <option value="enum">ENUM</option>
            <option value="float">FLOAT</option>
            <option value="int">INT</option>
            <option value="json">JSON</option>
            <option value="mediumInteger">MEDIUMINT</option>
            <option value="smallint">SMALLINT</option>
            <option value="text">TEXT</option>
            <option value="time">TIME</option>
            <option value="timestamp">TIMESTAMP</option>
            <option value="tinyint">TINYINT</option>
            <option value="varchar">VARCHAR</option>
            <option value="year">YEAR</option>
        `;

        var pgsqlOptions = `
            <option value="bigint">BIGINT</option>
            <option value="bit">BIT</option>
            <option value="bit varying">BIT VARYING</option>
            <option value="boolean">BOOLEAN</option>
            <option value="box">BOX</option>
            <option value="bytea">BYTEA</option>
            <option value="char">CHAR</option>
            <option value="cidr">CIDR</option>
            <option value="circle">CIRCLE</option>
            <option value="date">DATE</option>
            <option value="daterange">DATERANGE</option>
            <option value="decimal">DECIMAL</option>
            <option value="double precision">DOUBLE PRECISION</option>
            <option value="enum">ENUM</option>
            <option value="hstore">HSTORE</option>
            <option value="inet">INET</option>
            <option value="int4range">INT4RANGE</option>
            <option value="int8range">INT8RANGE</option>
            <option value="interval">INTERVAL</option>
            <option value="json">JSON</option>
            <option value="jsonb">JSONB</option>
            <option value="line">LINE</option>
            <option value="lseg">LSEG</option>
            <option value="macaddr">MACADDR</option>
            <option value="money">MONEY</option>
            <option value="numeric">NUMERIC</option>
            <option value="oid">OID</option>
            <option value="path">PATH</option>
            <option value="point">POINT</option>
            <option value="polygon">POLYGON</option>
            <option value="real">REAL</option>
            <option value="serial">SERIAL</option>
            <option value="smallint">SMALLINT</option>
            <option value="text">TEXT</option>
            <option value="time">TIME</option>
            <option value="time with time zone">TIME WITH TIME ZONE</option>
            <option value="timestamp">TIMESTAMP</option>
            <option value="timestamp with time zone">TIMESTAMP WITH TIME ZONE</option>
            <option value="tsquery">TSQUERY</option>
            <option value="tsrange">TSRANGE</option>
            <option value="tsvector">TSVECTOR</option>
            <option value="tstzrange">TSTZRANGE</option>
            <option value="uuid">UUID</option>
            <option value="varchar">VARCHAR</option>
            <option value="xml">XML</option>
        `;

        var mongodbOptions = `
            <option value="string">String</option>
            <option value="int">Integer</option>
            <option value="double">Double</option>
            <option value="decimal">Decimal</option>
            <option value="bool">Boolean</option>
            <option value="date">Date</option>
            <option value="object">Object</option>
            <option value="array">Array</option>
            <option value="binary">Binary Data</option>
            <option value="null">Null</option>
            <option value="objectId">ObjectId</option>
        `;

        // Event listener for database type selection
        $('#db_type').on('change', function() {
            var selectedDbType = $(this).val();
            var fieldTypeSelect = $('#fieldType');

            // Clear previous options
            fieldTypeSelect.html('<option value="">Select Field Type</option>');

            // Append the correct options based on the selected database type
            if (selectedDbType === 'mysql') {
                fieldTypeSelect.append(mysqlOptions);
            } else if (selectedDbType === 'pgsql') {
                fieldTypeSelect.append(pgsqlOptions);
            } else if (selectedDbType === 'mongodb') {
                fieldTypeSelect.append(mongodbOptions);
            }
        });




        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        $('#create-connection-btn').click(function() {
            // Get values from the input fields
            const name = $('#module_name').val();
            const dbType = $('#db_type').val();
            const dbHost = $('#db_host').val();
            const dbPort = $('#db_port').val();
            const dbName = $('#db_name').val();
            const dbUsername = $('#db_user').val();
            const dbPassword = $('#db_password').val();

            // if (dbType === 'mysql') {
            //     $('#mysql-options').show();
            //     $('#pgsql-options').html(''); // Clear PostgreSQL selection
            //     $('#mongodb-options').html('');
            // } else if (dbType === 'pgsql') {
            //     $('#pgsql-options').show();
            //     $('#mysql-options').hide();
            //     $('#mongodb-options').hide();
            //     $('#mysql-options').html(''); // Clear MySQL selection
            //     $('#mongodb-options').html('');
            // } else if (dbType === 'mongodb') {
            //     $('#mongodb-options').show();
            //     $('#mysql-options').hide();
            //     $('#pgsql-options').hide();
            //     $('#mysql-options').html(''); // Clear MySQL selection
            //     $('#pgsql-options').html('');
            // }

            // Perform validation if necessary
            if (!name || !dbType || !dbHost || !dbPort || !dbName) {
                alert("Please fill in all required fields.");
                return;
            }

            // Prepare data for the AJAX request
            const data = {
                name: name,
                db_type: dbType,
                db_host: dbHost,
                db_port: dbPort,
                db_name: dbName,
                db_username: dbUsername,
                db_password: dbPassword
            };



            // AJAX request to your backend
            $.ajax({
                url: '/create-connection', // Update with your backend URL
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                beforeSend: function() {
                    loader.show();
                },
                success: function(response) {


                    // You can also handle further actions based on response
                    setTimeout(function() {
                        $.ajax({
                            url: '/optimize',
                            type: 'GET',
                            success: function(optimizeResponse) {
                                alert("Connection created successfully: " +
                                    response.message);
                                loader.hide();
                            },
                            error: function() {

                            }
                        });
                    }, 2500);
                },
                error: function(xhr) {
                    alert("Error creating connection: " + xhr.responseJSON
                        .message || xhr
                        .statusText);
                }
            });

        });



        // Track the number of fields
        const dbTypeOptions = {
            mysql: [{
                    value: 'bigInteger',
                    text: 'BIGINT'
                },
                {
                    value: 'boolean',
                    text: 'BOOLEAN'
                },
                {
                    value: 'char',
                    text: 'CHAR'
                },
                {
                    value: 'date',
                    text: 'DATE'
                },
                {
                    value: 'dateTime',
                    text: 'DATETIME'
                },
                {
                    value: 'decimal',
                    text: 'DECIMAL'
                },
                {
                    value: 'double',
                    text: 'DOUBLE'
                },
                {
                    value: 'enum',
                    text: 'ENUM'
                },
                {
                    value: 'float',
                    text: 'FLOAT'
                },
                {
                    value: 'integer',
                    text: 'INT'
                },
                {
                    value: 'json',
                    text: 'JSON'
                },
                {
                    value: 'mediumInteger',
                    text: 'MEDIUMINT'
                },
                {
                    value: 'smallInteger',
                    text: 'SMALLINT'
                },
                {
                    value: 'text',
                    text: 'TEXT'
                },
                {
                    value: 'time',
                    text: 'TIME'
                },
                {
                    value: 'timestamp',
                    text: 'TIMESTAMP'
                },
                {
                    value: 'tinyInteger',
                    text: 'TINYINT'
                },
                {
                    value: 'string',
                    text: 'VARCHAR'
                }, // 'string' is used in Laravel for VARCHAR
                {
                    value: 'year',
                    text: 'YEAR'
                }
            ],

            pgsql: [{
                    value: 'bigint',
                    text: 'BIGINT'
                },
                {
                    value: 'bit',
                    text: 'BIT'
                },
                {
                    value: 'bitVarying',
                    text: 'BIT VARYING'
                },
                {
                    value: 'boolean',
                    text: 'BOOLEAN'
                },
                {
                    value: 'box',
                    text: 'BOX'
                },
                {
                    value: 'bytea',
                    text: 'BYTEA'
                },
                {
                    value: 'char',
                    text: 'CHAR'
                },
                {
                    value: 'cidr',
                    text: 'CIDR'
                },
                {
                    value: 'circle',
                    text: 'CIRCLE'
                },
                {
                    value: 'date',
                    text: 'DATE'
                },
                {
                    value: 'daterange',
                    text: 'DATERANGE'
                },
                {
                    value: 'decimal',
                    text: 'DECIMAL'
                },
                {
                    value: 'double precision',
                    text: 'DOUBLE PRECISION'
                },
                {
                    value: 'enum',
                    text: 'ENUM'
                },
                {
                    value: 'hstore',
                    text: 'HSTORE'
                },
                {
                    value: 'inet',
                    text: 'INET'
                },
                {
                    value: 'int4range',
                    text: 'INT4RANGE'
                },
                {
                    value: 'int8range',
                    text: 'INT8RANGE'
                },
                {
                    value: 'interval',
                    text: 'INTERVAL'
                },
                {
                    value: 'json',
                    text: 'JSON'
                },
                {
                    value: 'jsonb',
                    text: 'JSONB'
                },
                {
                    value: 'line',
                    text: 'LINE'
                },
                {
                    value: 'lseg',
                    text: 'LSEG'
                },
                {
                    value: 'macaddr',
                    text: 'MACADDR'
                },
                {
                    value: 'money',
                    text: 'MONEY'
                },
                {
                    value: 'numeric',
                    text: 'NUMERIC'
                },
                {
                    value: 'oid',
                    text: 'OID'
                },
                {
                    value: 'path',
                    text: 'PATH'
                },
                {
                    value: 'point',
                    text: 'POINT'
                },
                {
                    value: 'polygon',
                    text: 'POLYGON'
                },
                {
                    value: 'real',
                    text: 'REAL'
                },
                {
                    value: 'serial',
                    text: 'SERIAL'
                },
                {
                    value: 'smallint',
                    text: 'SMALLINT'
                },
                {
                    value: 'text',
                    text: 'TEXT'
                },
                {
                    value: 'time',
                    text: 'TIME'
                },
                {
                    value: 'time with time zone',
                    text: 'TIME WITH TIME ZONE'
                },
                {
                    value: 'timestamp',
                    text: 'TIMESTAMP'
                },
                {
                    value: 'timestampTz',
                    text: 'TIMESTAMP WITH TIME ZONE'
                },
                {
                    value: 'tsquery',
                    text: 'TSQUERY'
                },
                {
                    value: 'tsrange',
                    text: 'TSRANGE'
                },
                {
                    value: 'tsvector',
                    text: 'TSVECTOR'
                },
                {
                    value: 'tstzrange',
                    text: 'TSTZRANGE'
                },
                {
                    value: 'uuid',
                    text: 'UUID'
                },
                {
                    value: 'varchar',
                    text: 'VARCHAR'
                },
                {
                    value: 'xml',
                    text: 'XML'
                }
            ],
            mongodb: [{
                    value: 'ObjectId',
                    text: 'ObjectId'
                }, // Used for unique document identifiers
                {
                    value: 'String',
                    text: 'String'
                }, // Text values
                {
                    value: 'Number',
                    text: 'Number'
                }, // Numeric values (int, long, double)
                {
                    value: 'Boolean',
                    text: 'Boolean'
                }, // True or false
                {
                    value: 'Date',
                    text: 'Date'
                }, // Date values
                {
                    value: 'Array',
                    text: 'Array'
                }, // Array of values
                {
                    value: 'Object',
                    text: 'Object'
                }, // Embedded document
                {
                    value: 'Null',
                    text: 'Null'
                }, // Null value
                {
                    value: 'Binary',
                    text: 'Binary'
                }, // Binary data
                {
                    value: 'RegExp',
                    text: 'RegExp'
                } // Regular expression
            ]
        };

        // Populate field type options based on the selected dbType
        function populateFieldOptions(dbType, fieldIndex) {
            console.log(dbType, "dbType")
            let fieldTypeSelector = $(`#fieldType${fieldIndex}`);
            fieldTypeSelector.empty(); // Clear previous options
            let options = dbTypeOptions[dbType] || [];
            options.forEach(option => {
                fieldTypeSelector.append(new Option(option.text, option.value));
            });
        }

        // Initial population of the first field set based on default dbType
        // Populate for first field

        // Change the type options when the dbType changes
        $('#dbType').change(function() {
            currentDbType = $(this).val();
            for (let i = 0; i < fieldIndex; i++) {
                populateFieldOptions(currentDbType,
                    i); // Update options for each existing field set
            }
        });

        // Function to add a new field set
        $('#add-field').click(function() {
            let currentDbType = $('#db_type').val();
            console.log(currentDbType, "currentDbType")
            populateFieldOptions(currentDbType, 0);
            let newFieldSet = `
        <div class="row mb-3" id="field-set-${fieldIndex}">
            <div class="col-md-3">
                <label for="attribute${fieldIndex}" class="form-label">Attribute ${fieldIndex + 1}</label>
                <input type="text" class="form-control" name="fields[${fieldIndex}][attribute]" placeholder="Enter Attribute" required>
            </div>
            <div class="col-md-3">
                <label for="type${fieldIndex}" class="form-label">Type</label>
                <select class="form-control" name="fields[${fieldIndex}][type]" id="fieldType${fieldIndex}">
                    <!-- Options will be dynamically populated -->
                </select>
            </div>
            <div class="col-md-3">
                <label for="length${fieldIndex}" class="form-label">Length</label>
                <input type="number" class="form-control" name="fields[${fieldIndex}][length]" placeholder="Enter Length (if applicable)">
            </div>
             <div class="col-md-3">
                <label for="value${fieldIndex}" class="form-label">Value</label>
                <input type="text" class="form-control" name="fields[${fieldIndex}][value]" placeholder="Enter Value">
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" name="fields[${fieldIndex}][nullable]" id="nullable${fieldIndex}" value="1">
                    <label class="form-check-label" for="nullable${fieldIndex}">Nullable</label>
                </div>
            </div>
        </div>
        `;

            $('#dynamic-field-container').append(newFieldSet); // Append the new field set
            populateFieldOptions(currentDbType,
                fieldIndex); // Populate type options for the new field set
            fieldIndex++; // Increment the index for the next set
        });

        // Function to remove the last field set
        $('#remove-field').click(function() {
            if (fieldIndex > 1) {
                fieldIndex--; // Decrease the index
                $('#field-set-' + fieldIndex).remove(); // Remove the last added field set
            } else {
                alert('At least one field is required.'); // Ensure at least one set remains
            }
        });


        // let fieldIndex = 1;
        // $('#add-field').click(function() {
        //     let container = $('#dynamic-field-container');

        //     let newFieldSet = `
        // <div class="row mb-3">
        //     <!-- Attribute Field -->
        //     <div class="col-md-3">
        //         <label for="attribute${fieldIndex}" class="form-label">Attribute ${fieldIndex + 1}</label>
        //         <input type="text" class="form-control" name="fields[${fieldIndex}][attribute]" placeholder="Enter Attribute" required>
        //     </div>

        //     <!-- Type Field -->
        //     <div class="col-md-3">
        //         <label for="type${fieldIndex}" class="form-label">Type</label>
        //         <select class="form-control" name="fields[${fieldIndex}][type]" required>
        //             <option value="">Select Type</option>
        //             <option value="bigint">BIGINT</option>
        //             <option value="bigserial">BIGSERIAL</option>
        //             <option value="bit">BIT</option>
        //             <option value="bit varying">BIT VARYING</option>
        //             <option value="boolean">BOOLEAN</option>
        //             <option value="box">BOX</option>
        //             <option value="bytea">BYTEA</option>
        //             <option value="char">CHAR</option>
        //             <option value="cidr">CIDR</option>
        //             <option value="circle">CIRCLE</option>
        //             <option value="date">DATE</option>
        //             <option value="daterange">DATERANGE</option>
        //             <option value="decimal">DECIMAL</option>
        //             <option value="double precision">DOUBLE PRECISION</option>
        //             <option value="enum">ENUM</option>
        //             <option value="hstore">HSTORE</option>
        //             <option value="inet">INET</option>
        //             <option value="int4range">INT4RANGE</option>
        //             <option value="int8range">INT8RANGE</option>
        //             <option value="interval">INTERVAL</option>
        //             <option value="json">JSON</option>
        //             <option value="jsonb">JSONB</option>
        //             <option value="line">LINE</option>
        //             <option value="lseg">LSEG</option>
        //             <option value="macaddr">MACADDR</option>
        //             <option value="money">MONEY</option>
        //             <option value="numeric">NUMERIC</option>
        //             <option value="oid">OID</option>
        //             <option value="path">PATH</option>
        //             <option value="point">POINT</option>
        //             <option value="polygon">POLYGON</option>
        //             <option value="real">REAL</option>
        //             <option value="serial">SERIAL</option>
        //             <option value="smallint">SMALLINT</option>
        //             <option value="text">TEXT</option>
        //             <option value="time">TIME</option>
        //             <option value="time with time zone">TIME WITH TIME ZONE</option>
        //             <option value="timestamp">TIMESTAMP</option>
        //             <option value="timestamp with time zone">TIMESTAMP WITH TIME ZONE</option>
        //             <option value="tsquery">TSQUERY</option>
        //             <option value="tsrange">TSRANGE</option>
        //             <option value="tsvector">TSVECTOR</option>
        //             <option value="tstzrange">TSTZRANGE</option>
        //             <option value="uuid">UUID</option>
        //             <option value="varchar">VARCHAR</option>
        //             <option value="xml">XML</option>
        //         </select>
        //     </div>

        //     <!-- Length Field -->
        //     <div class="col-md-3">
        //         <label for="length${fieldIndex}" class="form-label">Length</label>
        //         <input type="number" class="form-control" name="fields[${fieldIndex}][length]" placeholder="Enter Length (if applicable)">
        //     </div>

        //     <!-- Nullable Checkbox -->
        //     <div class="col-md-3 d-flex align-items-center">
        //         <div class="form-check mt-3">
        //             <input class="form-check-input" type="checkbox" name="fields[${fieldIndex}][nullable]" id="nullable${fieldIndex}" value="1">
        //             <label class="form-check-label" for="nullable${fieldIndex}">
        //                 Nullable
        //             </label>
        //         </div>
        //     </div>
        // </div>
        // `;

        //     container.append(newFieldSet);
        //     fieldIndex++; // Increment index for the next set
        // });

        // // Remove field function
        // $('#remove-field').click(function() {
        //     let container = $('#dynamic-field-container');
        //     if (container.children().length > 1) { // Only remove if more than one set exists
        //         container.children().last().remove();
        //         fieldIndex--; // Decrement index
        //     }
        // });



        $('#enable-db-connection').change(function() {
            $('#db-connection-fields').toggle(this.checked);
        });
       
        $(document).on('change', '.toggle-status', function() {
            // Get the module ID from the data-id attribute of the changed element
            var moduleId = $(this).data('id');
            // Determine the status based on whether the checkbox is checked or not
            var status = $(this).prop('checked') ? '1' : '0';
            loader.show();
            // Send an AJAX request to update the module status on the server
            $.ajax({            
                url: appUrl + '/api/advance-module/toggle-status',
                type: 'POST',
                data: {            
                    id: moduleId,
                    status: status
                },

                success: function(response) {
                    // If the server response indicates success
                    if (response.success) {
                        $.ajax({
                            url: '/optimize', // Replace with your optimize route
                            type: 'GET',
                            success: function(optimizeResponse) {
                                if (response.success) {
                                    loader.hide();
                                    alert(
                                        'Status updated successfully');
                                }
                            }
                        })

                        // Show a success message to the user

                    } else {
                        // Show an error message if the status update failed
                        alert('Failed to update status');
                    }
                },
                // Function to call if the request fails
                error: function() {
                    // Show a generic error message
                    alert('Error updating status');
                }
            });
        });
    });


    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("openModalBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal
    btn.onclick = function() {
        $(".updateBtn").hide();
        $(".submitBtn").show();
        $("#create-connection-btn").show();
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal and clear alerts
    span.onclick = function() {
        modal.style.display = "none";
        clearAlerts();
    }

    // When the user clicks anywhere outside of the modal, close it and clear alerts
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            clearAlerts();
        }
    }

    // Function to clear alert messages
    function clearAlerts() {
        document.getElementById("errorAlert").innerHTML = '';
        document.getElementById("successAlert").innerHTML = '';
    }






    // function toggleParentIdField() {
    //     var selectBox = document.getElementById("is_parent_input");
    //     var parentIdField = document.getElementById("parentIdField");

    //     if (selectBox.value == "0") {
    //         parentIdField.style.display = "block";
    //         document.getElementById("parentIdField").value = '';

    //     } else {
    //         parentIdField.style.display = "none";
    //         document.getElementById("parentIdField").value = '';

    //     }
    // }

    // Call the function on page load to ensure the field is shown/hidden based on the old value
    // window.onload = function() {
    //     toggleParentIdField();
    // };

