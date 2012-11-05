function enableGroup(elem) {
  for(i=0;i<elem.length;i++) {
    elem[i].disabled = false;
  }
}

function disableGroup(elem) {
  for(i=0;i<elem.length;i++) {
    elem[i].disabled = true;
  }
}
