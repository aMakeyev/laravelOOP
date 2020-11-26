<?php namespace Calc\Helpers;


class Lists
{
    public static function clients($all = null, $term = null)
    {
        $out = [];

        if ($all == 'all')
        {
            $out[] = ['id' => 0, 'title' => 'Все'];
        }

        $query = \Calc\Model\Client::query();
        $query->select(['id','first_name','last_name','email']);

        if (ctype_digit($all))
        {
            $query->where('id', (int) $all);

            return $query->first();
        }

        if ($term)
        {
            if (strpos($term, ' '))
            {
                list($last_name, $first_name) = explode(' ', $term, 2);
                $query->where('first_name', 'LIKE', "{$first_name}%");
                $query->orWhere('last_name', 'LIKE', "{$last_name}%");
            }
            else
            {
                $query->where('first_name', 'LIKE', "%{$term}%");
                $query->orWhere('last_name', 'LIKE', "%{$term}%");
                $query->orWhere('email', 'LIKE', "%{$term}%");
            }
        }

        foreach ($query->get() as $entity)
        {
            $out[] = $entity->toArray();
        }

        return $out;
    }

    public static function contractors($all = null, $term = null)
    {
        $out = [];

        if ($all == 'all')
        {
            $out[] = ['id' => 0, 'title' => 'Все'];
        }

        $query = \Calc\Model\Contractor::query();
        $query->select(['id','first_name','last_name','title','email','phone']);

        if (ctype_digit($all))
        {
            $query->where('id', (int) $all);

            return $query->first();
        }

        if ($term)
        {
            if (strpos($term, ' '))
            {
                list($last_name, $first_name) = explode(' ', $term, 2);
                $query->where('first_name', 'LIKE', "{$first_name}%");
                $query->orWhere('last_name', 'LIKE', "{$last_name}%");
                $query->orWhere('title', 'LIKE', "{$term}%");
            }
            else
            {
                $query->where('first_name', 'LIKE', "%{$term}%");
                $query->orWhere('last_name', 'LIKE', "%{$term}%");
                $query->orWhere('email', 'LIKE', "%{$term}%");
                $query->orWhere('title', 'LIKE', "%{$term}%");
            }
        }

        foreach ($query->get() as $entity)
        {
            $out[] = $entity->toArray();
        }

        return $out;
    }

    public static function managers($all = null, $term = null)
    {
        $out = [];

        if ($all == 'all')
        {
            $out[] = ['id' => 0, 'text' => 'Все'];
        }

        $query = \Calc\Model\User::query();
        $query->select(['id','first_name','last_name'])->where('role', '!=', 4);

        if (ctype_digit($all))
        {
            $query->where('id', (int) $all);

            return $query->first();
        }

        if ($term)
        {
            if (strpos($term, ' '))
            {
                list($last_name, $first_name) = explode(' ', $term, 2);
                $query->where('first_name', 'LIKE', "{$first_name}%");
                $query->orWhere('last_name', 'LIKE', "{$last_name}%");
            }
            else
            {
                $query->where('first_name', 'LIKE', "%{$term}%");
                $query->orWhere('last_name', 'LIKE', "%{$term}%");
            }
        }

        foreach ($query->get() as $entity)
        {
            $out[] = ['id' => $entity->id, 'text' => "{$entity->last_name} {$entity->first_name}"];
        }

        return $out;
    }

    public static function parts($all = null, $term = null)
    {
        $out = [];

        if ($all == 'all')
        {
            $out[] = ['id' => 0, 'title' => 'Все'];
        }

        $query = \Calc\Model\Part::query();
        $query->select(['id','title','unit','unit_price']);

        if (ctype_digit($all))
        {
            $query->where('id', (int) $all);

            return $query->first();
        }

        if ($term)
        {
            $query->where('title', 'LIKE', "%{$term}%");
        }

        foreach ($query->get() as $entity)
        {
            $out[] = $entity->toArray();
        }

        return $out;
    }

    //Шатерсы

	public static function shatersClients($all = null, $term = null)
	{
		$out = [];

		if ($all == 'all')
		{
			$out[] = ['id' => 0, 'title' => 'Все'];
		}

		$query = \Calc\Model\ShatersClient::query();
		$query->select(['id','first_name','last_name','email']);

		if (ctype_digit($all))
		{
			$query->where('id', (int) $all);

			return $query->first();
		}

		if ($term)
		{
			if (strpos($term, ' '))
			{
				list($last_name, $first_name) = explode(' ', $term, 2);
				$query->where('first_name', 'LIKE', "{$first_name}%");
				$query->orWhere('last_name', 'LIKE', "{$last_name}%");
			}
			else
			{
				$query->where('first_name', 'LIKE', "%{$term}%");
				$query->orWhere('last_name', 'LIKE', "%{$term}%");
				$query->orWhere('email', 'LIKE', "%{$term}%");
			}
		}

		foreach ($query->get() as $entity)
		{
			$out[] = $entity->toArray();
		}

		return $out;
	}

	public static function shatersParts($all = null, $term = null)
	{
		$out = [];

		if ($all == 'all')
		{
			$out[] = ['id' => 0, 'title' => 'Все'];
		}

		$query = \Calc\Model\ShatersPart::query();
		$query->select(['id','title','unit','unit_price']);

		if (ctype_digit($all))
		{
			$query->where('id', (int) $all);

			return $query->first();
		}

		if ($term)
		{
			$query->where('title', 'LIKE', "%{$term}%");
		}

		foreach ($query->get() as $entity)
		{
			$out[] = $entity->toArray();
		}

		return $out;
	}

	public static function shatersManagers($all = null, $term = null)
	{
		$out = [];

		if ($all == 'all')
		{
			$out[] = ['id' => 0, 'text' => 'Все'];
		}

		$query = \Calc\Model\User::query();
		$query->select(['id','first_name','last_name'])->where('role', '!=', 2);

		if (ctype_digit($all))
		{
			$query->where('id', (int) $all);

			return $query->first();
		}

		if ($term)
		{
			if (strpos($term, ' '))
			{
				list($last_name, $first_name) = explode(' ', $term, 2);
				$query->where('first_name', 'LIKE', "{$first_name}%");
				$query->orWhere('last_name', 'LIKE', "{$last_name}%");
			}
			else
			{
				$query->where('first_name', 'LIKE', "%{$term}%");
				$query->orWhere('last_name', 'LIKE', "%{$term}%");
			}
		}

		foreach ($query->get() as $entity)
		{
			$out[] = ['id' => $entity->id, 'text' => "{$entity->last_name} {$entity->first_name}"];
		}

		return $out;
	}
}

