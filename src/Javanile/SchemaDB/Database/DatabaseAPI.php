<?php
/**
 *
 *
\*/
namespace Javanile\SchemaDB\Database;

/**
 *
 *
 *
 */
class DatabaseAPI extends DatabaseCommon
{
    /**
	 *
	 *
	 * @param type $confirm
	 * @return type
	 */
	public function drop($confirm) {

		if ($confirm != 'confirm') {
			return;
		}

		//
		$tables = $this->getTables();

		//
		if (!$tables) {
			return;
		}

		//
		foreach($tables as $table) {

			//
			$sql = "DROP TABLE `{$table}`";

			//
			$this->execute($sql);
		}
	}
		
    /**
     *
     *
     */
    public function dump($model=null) {

        //
        if ($model) {

            //
            $all = $this->all($model);

            //
            Debug::grid_dump($model,$all);
        }

        //
        else {
            $this->dumpSchema();
        }

    }
}