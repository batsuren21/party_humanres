UPDATE person SET 
PersonLastLetter=SUBSTRING(PersonLastName,1,1),
PersonFirstLetter=SUBSTRING(PersonFirstName,1,1)
