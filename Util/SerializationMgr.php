<?php


class SerializationMgr {

	public function serialize($object) {
//		foreach($object as $key => $value) {
//			if($value == null) {
//				unset($object[key]);
//			}
//		}

		$serializedObject = json_encode($object);

		return $serializedObject;
	}

	public function deserialize($serializedObject, $objectType) {
		$propertyArray = json_decode($serializedObject);
		if(is_array($propertyArray)) {
			$object = Array();
			for($i=0; $i < count($propertyArray); $i++) {
				array_push($object, new DomainObject($objectType, $propertyArray[$i]));
			}
		}
		else {
			$object = new DomainObject($objectType, $propertyArray);
		}

		return $object;
	}
}

/*   public class SerializationMgr
    {

        public virtual string Serialize(object objectToSerialize)
        {
            MemoryStream stream = new MemoryStream();
            DataContractJsonSerializer serializer = new DataContractJsonSerializer(objectToSerialize.GetType());
            serializer.WriteObject(stream, objectToSerialize);
            stream.Position = 0;
            StreamReader reader = new StreamReader(stream);

            return reader.ReadToEnd();
        }

        public virtual T Deserialize<T>(Stream stream) where T : class
        {
            DataContractJsonSerializer serializer = new DataContractJsonSerializer(typeof(T));
            T deserializedObject = serializer.ReadObject(stream) as T;

            return deserializedObject;
        }

    }
*/


