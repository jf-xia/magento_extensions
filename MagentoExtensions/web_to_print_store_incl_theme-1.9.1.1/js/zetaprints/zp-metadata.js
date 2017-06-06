function zp_set_metadata (field, key, value) {
  if (!key)
    zp_clear_metadata(field);
  else if (typeof key === 'object')
    field.metadata = key;
  else {
    if (!field.metadata)
      field.metadata = {};

    field.metadata[key] = value;
  }
}

function zp_get_metadata (field, key, default_value) {
  if (!field.metadata || !field.metadata[key])
    return default_value;

  return field.metadata[key];
}

function zp_clear_metadata (field) {
  field.metadata = undefined;
}

function zp_convert_metadata_to_string (field) {
  if (!field.metadata)
    return null;

  var s = '';

  for (var key in field.metadata)
    if (field.metadata[key] !== undefined)
      s += key + '=' + field.metadata[key] + ';';

  return s.substring(0, s.length - 1);
}
