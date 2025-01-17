<?php

class UserController extends \BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (!Auth::user()->hasAccess('user.show'))
        {
            App::abort(403);
        }

        return View::make('back.user.index', array(
            'users' => User::with('group')->orderBy('lastname', 'desc')->paginate()
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if (!Auth::user()->hasAccess(array(
            'user.create.Beheerder',
            'user.create.Leraar',
            'user.create.Leerling'
        ), false))
        {
            App::abort(403);
        }

        $groups = array();

        foreach (Group::all() as $group)
        {
            $groups[$group->id] = $group->name;
        }

        return View::make('back.user.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $user = Auth::user();

        // No permission to create any type of account
        if (!$user->hasAccess(array(
            'user.create.Beheerder',
            'user.create.Leraar',
            'user.create.Leerling'
        ), false))
        {
            App::abort(403);
        }

        $input = Input::all();

        $validator = User::validate($input);

        if ($validator->passes())
        {
            // Find group by input id
            $group = Group::find($input['group_id']);

            // Check if user may create new user in that group
            if (!$user->hasAccess('user.create.' . $group->name))
            {
                return Redirect::route('admin.users.create')->withInput()->withErrors('Je mag geen gebruikers van type ' . $group->name . ' maken.');
            }

            // Create new user
            $new_user = new User($input);

            // Create random, password
            $password = Str::random(8);
            $new_user->password = Hash::make($password);

            $new_user->save();

            // TODO: async this
            // TODO: Make use email instead of screen
            /*
            Mail::send('emails.new_user', compact('new_user', 'password'), function($message) use ($new_user)
            {
                $message->to($new_user->email)->subject('Welkom!');
            });

            return Redirect::route('admin.users.index');
            */

            return View::make('back.user.store', compact('new_user', 'password'));
        }
        else
        {
            return Redirect::route('admin.users.create')->withInput()->withErrors($validator);
        }
    }

	/**
     * Edits an user
     *
     * @return Response
     */
	public function edit($id)
	{
		$user = Auth::user();
        $edit_user = User::find($id);

		$groups = array();

        if(!$user->hasAccess('user.update.Beheerder'))
        {
            App::abort(403);
        }

		foreach (Group::all() as $group)
        {
            $groups[$group->id] = $group->name;
        }

        return View::make('back.user.edit', compact('edit_user', 'groups'));
	}

	/**
     * Updates an user
     *
     * @return Response
     */
	public function update($id)
	{
		$user = Auth::user();
        $edit_user = User::findOrFail($id);

        if(!$user->hasAccess('user.update.Beheerder'))
        {
            App::abort(403);
        }

		$input = Input::all();

        $validator = User::validateUpdate($input);

        if ($validator->passes())
        {
            // Find group by input id
            $group = Group::find($input['group_id']);

            // Check if user may create new user in that group
            if (!$user->hasAccess('user.edit.' . $group->name))
            {
                return Redirect::route('admin.users.edit')->withInput()->withErrors('Je mag geen gebruikers van type ' . $group->name . ' maken.');
            }

			$user = $this->updateUser($edit_user);
			$user->save();

			return Redirect::route('admin.users.index')->withErrors('De gebruiker "' . $edit_user->firstname . ' ' . $edit_user->lastname . '" met het email adres "' . $edit_user->email . '" is succesvol aangepast.');
		}
		else
        {
            return Redirect::route('admin.users.index')->withInput()->withErrors($validator);
        }
	}

	/**
     * Deletes an user
     *
     * @return Response
     */
	public function destroy($id)
	{
		$user = Auth::user();
		$edit_user = User::findOrFail($id);

		if(!$user->hasAccess('user.update.other'))
		{
			App::abort(403);
		}

		$redirect_string = 'De gebruiker "' . $edit_user->firstname . ' ' . $edit_user->lastname . '" met het email adres "' . $edit_user->email . '" is succesvol verwijderd.';

		// Destroy the user
		$edit_user->delete();

		return Redirect::route('admin.users.index')->withErrors($redirect_string);
	}

    /**
     * Log a User in
     *
     * @return Response
     */
    public function login()
    {
        // Get email & pass from Input
        $input = array(
            'email'    => Input::get('email'),
            'password' => Input::get('pass')
        );

        // Check Input
        $validator = User::validateLogin($input);

        if ($validator->passes())
        {
            // Attempt a login
            if (Auth::attempt($input))
            {
                // Redirect to admin panel
                return Redirect::intended('admin');
            }
            // Invalid user
            else
            {
                // Back to login
                // With error
                // With Input
                return Redirect::route('login')->withErrors('Deze combinatie van email en wachtwoord is niet bekend.')
                    ->withInput(Input::except('password'));
            }
        }
        // Invalid input
        else
        {
            // Back to login
            // With errors
            // With input, except password
            return Redirect::route('login')->withErrors($validator)
                ->withInput(Input::except('password'));
        }
    }

    /**
     * Log a User out.
     *
     * @return Response
     */
    public function logout()
    {
        Auth::logout();

        return Redirect::route('login');
    }
}
