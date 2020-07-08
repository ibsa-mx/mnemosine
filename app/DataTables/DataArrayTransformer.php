<?php

namespace Mnemosine\DataTables;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class DataArrayTransformer
{
    /**
     * Transform row data by columns definition.
     *
     * @param array  $row
     * @param mixed  $columns
     * @param string $type
     * @return array
     */
    public function transform(array $row, $columns, $type = 'printable')
    {
        if ($columns instanceof Collection) {
            return $this->buildColumnByCollection($row, $columns, $type);
        }

        return Arr::only($row, $columns);
    }

    /**
     * Transform row column by collection.
     *
     * @param array                          $row
     * @param \Illuminate\Support\Collection $columns
     * @param string                         $type
     * @return array
     */
    protected function buildColumnByCollection(array $row, Collection $columns, $type = 'printable')
    {
        $results = [];
        foreach ($columns->all() as $column) {
            if ($column[$type]) {
                $title = $column['title'];
                $data  = Arr::get($row, $column['data']);
                if ($type == 'exportable') {
                    $title    = $this->decodeContent($title);
                    if(strpos($column['name'], "photography.") !== false && !empty($data)){
                        if (preg_match('/src="([^"]+)"/iU', $data, $matches)) {
                            $data = $matches[1];
                        }
                    } else{
                        $dataType = gettype($data);
                        $data     = $this->decodeContent($data);
                        settype($data, $dataType);
                    }
                }

                $results[$title] = $data;
            }
        }

        return $results;
    }

    /**
     * Decode content to a readable text value.
     *
     * @param string $data
     * @return string
     */
    protected function decodeContent($data)
    {
        try {
            $decoded = html_entity_decode(strip_tags($data), ENT_QUOTES, 'UTF-8');
            $search = array("\xc2\xa0", "\n", "\t");

            return trim(str_replace($search, ' ', $decoded));
        } catch (\Exception $e) {
            return $data;
        }
    }
}
