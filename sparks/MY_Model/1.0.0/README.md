A Base Model Codeigniter with validation support
====================

VERSION 1.0 STABLE  

Inspired by https://github.com/jamierumbelow/codeigniter-base-model.git  

A bit more explained documentation will be soon available.  

---Under development---

CONFIGURATION
---------------------

class Test\_model extends MY\_Model  
{  

> protected $\_table="tests"   //Default lowercase plural modelname  
> protected $primary\_key="id" //Default id  	
> protected $validate=array() //Set Codeigniter validation rules  
	
> /*If needed implement Hooks */  
> protected before\_find(){}  
> protected after\_find($rows){}  
	
> protected before\_insert($data){return $data;}  
> protected after\_insert($data,$insert\_id){}  
	
> protected before\_update($data){return $data;}  
> protected after\_update($data,$result){}  

> protected before\_delete(){}  
> protected after\_delete(){} 
 
}

METHODS
---------------------

### CREATE

+ insert($data,$skip\_validation)	
	
### READ

+ find($id)
+ find\_by\_{column\_name}($value,$limit,$offset)
+ find\_where($where\_codeigniter\_clause,$limit,$offset)
+ find\_all($limit,$offset)

### UPDATE

+ update($id,$data,$skip\_validation)				
+ update\_by\_{column\_name}($value,$data,$skip\_validation)
+ update\_where($where\_codeigniter\_clause,$data,$skip\_validation)
+ update\_all($data,$skip\_validation)		

### DELETE

+ delete($id)
+ delete\_by\_{column\_name}($value)
+ delete\_where($where\_codeigniter\_clause)
+ delete\_all()	

### ORM 
+ save($data,$skip\_validation)			